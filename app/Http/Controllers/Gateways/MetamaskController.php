<?php

namespace App\Http\Controllers\Gateways;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Currency;
use App\Models\CustomSettings;
use App\Models\GatewayProducts;
use App\Models\Gateways;
use App\Models\OldGatewayProducts;
use App\Models\PaymentPlans;
use App\Models\Setting;
use App\Models\Subscriptions as SubscriptionsModel;
use App\Models\SubscriptionItems;
use App\Models\HowitWorks;
use App\Models\User;
use App\Models\UserAffiliate;
use App\Models\UserOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Models\Coupon;

// use Srmklive\PayPal\Services\PayPal as PayPalClient;

// use App\Events\PaypalWebhookEvent;

/**
 * Controls ALL Payment actions of PayPal
 */
class MetamaskController extends Controller
{
    public static function subscribe($planId, $plan, $incomingException = null){

        $couponCode = request()->input('coupon');
        $newDiscountedPrice = $plan->price;
        if($couponCode){
            $coupone = Coupon::where('code', $couponCode)->first();
            if($coupone){
                $newDiscountedPrice  = $plan->price - ($plan->price * ($coupone->discount / 100));
                if ($newDiscountedPrice != floor($newDiscountedPrice)) {
                    $newDiscountedPrice = number_format($newDiscountedPrice, 2);
                }
            }
        }else{
            $coupone = null;
        }

        $gateway = Gateways::where("code", 'metamask')->first();
        if ($gateway == null) {
            abort(404);
        }

        $currency = Currency::where('id', $gateway->currency)->first()->code;
        $merchant_code = $gateway->live_client_id;
        $key = $gateway->live_client_secret;
        $exception = $incomingException;

        return view('panel.user.payment.subscription.payWithMetamask', compact('merchant_code', 'newDiscountedPrice','planId', 'plan' ,'exception'));
    }

    public static function getSubscriptionDaysLeft()
    {

        // $plan = PaymentPlans::find($request->plan);
        $userId=Auth::user()->id;
        $gateway = Gateways::where("code", "metamask")->first();
        if ($gateway == null) {
            abort(404);
        }

        $currency = Currency::where('id', $gateway->currency)->first()->code;

        if ($gateway->mode == 'sandbox') {
            config(['cashier.key' => $gateway->sandbox_client_id]);
            config(['cashier.secret' => $gateway->sandbox_client_secret]);
            config(['cashier.currency' => $currency]);
        } else {
            config(['cashier.key' => $gateway->live_client_id]); //$settings->stripe_key
            config(['cashier.secret' => $gateway->live_client_secret]); //$settings->stripe_secret
            config(['cashier.currency' => $currency]); //currency()->code
        }

        $activeSub = SubscriptionsModel::where([['stripe_status', '=', 'active'], ['user_id', '=', $userId]])->orWhere([['stripe_status', '=', 'trialing'], ['user_id', '=', $userId]])->first();
        if ($activeSub->status == 'active') {
            return \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::createFromTimeStamp($activeSub->current_period_end));
        } else {
            error_log($activeSub->trial_ends_at);
            return \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($activeSub->trial_ends_at));
        }

        // return $activeSub->current_period_end;

    }

    public function subscribePay(Request $request)
    {
        try{
            $previousRequest = app('request')->create(url()->previous());

            $gateway = Gateways::where("code", "paystack")->first();
            if ($gateway == null) {
                abort(404);
            }

            $orderId = $request->orderId;
            $productId = $request->productId;
            $planId = $request->planId;
            $billingPlanId = $request->billingPlanId;

            $payment_response = json_decode($request->response, true);
            $payment_response_status = $payment_response['status'];
            $payment_response_message = $payment_response['message'];
            $payment_response_reference = $payment_response['reference'];


            $plan = PaymentPlans::where('id', $planId)->first();
            $user = Auth::user();

            if ($gateway->mode == 'sandbox') {
                $key = $gateway->sandbox_client_secret;
            } else {
                $key = $gateway->live_client_secret;
            }

            


                if ($previousRequest->has('coupon')) {
                    $coupon = Coupon::where('code', $previousRequest->input('coupon'))->first();
                    if($coupon){
                        $coupon->usersUsed()->attach(auth()->user()->id);
                    }
                }


                $subscription = new SubscriptionsModel();
                $subscription->user_id = $user->id;
                $subscription->name = $planId;
                $subscription->stripe_id = "code_here";//$subscription_billing_code;
                $subscription->stripe_status = 'active';
                $subscription->stripe_price = $billingPlanId;
                $subscription->quantity = 1;
                $subscription->plan_id = $planId;
                $subscription->paid_with = 'paystack';
                $subscription->save();


                $subscriptionItem = new SubscriptionItems();
                $subscriptionItem->subscription_id = $subscription->id;
                $subscriptionItem->stripe_id = $orderId;
                $subscriptionItem->stripe_product = $productId;
                $subscriptionItem->stripe_price = $billingPlanId;
                $subscriptionItem->quantity = 1;
                $subscriptionItem->save();

                $payment->status = 'Success';
                $payment->save();

                $plan->total_words == -1? ($user->remaining_words = -1) : ($user->remaining_words += $plan->total_words);
                $plan->total_images == -1? ($user->remaining_images = -1) : ($user->remaining_images += $plan->total_images);

                $user->save();

                createActivity($user->id, __('Subscribed'), $plan->name.' '. __('Plan'), null);

                return redirect()->route('dashboard.index')->with(['message' => 'Thank you for your purchase. Enjoy your remaining words and images.', 'type' => 'success']);


        } catch (\Exception $th) {
            error_log("PaystackController::subscribePay(): ".$th->getMessage());
            return redirect()->route('dashboard.index')->with(['message' => $th->getMessage(), 'type' => 'error']);
        }
    }

    public function initiatePayment(Request $request)
    {
        // Generate a unique transaction ID
        $transactionId = uniqid('tx_');

        // Save the transaction details to your database
        // This is where you would typically save the transaction ID, user ID, and payment status
        // ...

        // Build the MetaMask payment URL
        $metaMaskUrl = "https://pay.sendwyre.com/purchase/{$transactionId}?destCurrency=ETH&dest=";

        // Return the URL to the frontend
        return response()->json(['success' => true, 'metaMaskUrl' => $metaMaskUrl]);
    }

}