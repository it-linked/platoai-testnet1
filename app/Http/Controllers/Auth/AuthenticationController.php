<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Jobs\SendConfirmationEmail;
use App\Models\Setting;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;
use App\Services\HubSpotService;


class AuthenticationController extends Controller
{
    protected $hubSpotService;

    public function __construct(HubSpotService $hubSpotService)
    {
        $this->hubSpotService = $hubSpotService;
    }
    public function githubCallback()
    {
        $githubUser = Socialite::driver('github')->user();
        $settings = Setting::first();

        $checkUser = User::where('email', $githubUser->getEmail())->exists();
        if ($checkUser) {
            $user = User::where('email', $githubUser->getEmail())->first();
            $user->github_token = $githubUser->token;
            $user->github_refresh_token = $githubUser->refreshToken;
            $user->avatar = $githubUser->getAvatar();
            $user->affiliate_code = $user->affiliate_code ?? Str::upper(Str::random(12));
            $user->save();
        } else {
            $user = User::updateOrCreate([
                'github_id' => $githubUser->id,
            ], [
                'name' => $githubUser->getName() ?? $githubUser->getNickname(),
                'surname' => '',
                'email' => $githubUser->getEmail(),
                'github_token' => $githubUser->token,
                'github_refresh_token' => $githubUser->refreshToken,
                'avatar' => $githubUser->getAvatar(),
                'remaining_words' => explode(',', $settings->free_plan)[0],
                'remaining_images' => explode(',', $settings->free_plan)[1] ?? 0,
                'password' => Hash::make(Str::random(12)),
                'affiliate_code' => Str::upper(Str::random(12)),
            ]);

            if($user){
                $this->createHubSpotContact($githubUser->getEmail(),$githubUser->getName() ?? $githubUser->getNickname());

            }
        }

        Auth::login($user);

        return redirect('/dashboard/user');
    }

    public function googleCallback()
    {
        $googleUser = Socialite::driver('google')->user();
        $checkUser = User::where('email', $googleUser->getEmail())->exists();
        $settings = Setting::first();

        $nameParts = explode(' ', $googleUser->getName());
        $name = $nameParts[0] ?? '';
        $surname = $nameParts[1] ?? '';

        if ($checkUser) {
            $user = User::where('email', $googleUser->getEmail())->first();
            $user->google_token = $googleUser->token;
            $user->google_refresh_token = $googleUser->refreshToken;
            $user->avatar = $googleUser->getAvatar();
            $user->affiliate_code = $user->affiliate_code ?? Str::upper(Str::random(12));
            $user->save();
        } else {
            $user = User::updateOrCreate([
                'google_id' => $googleUser->id,
            ], [
                'name' => $name,
                'surname' => $surname,
                'email' => $googleUser->getEmail(),
                'google_token' => $googleUser->token,
                'google_refresh_token' => $googleUser->refreshToken,
                'avatar' => $googleUser->getAvatar(),
                'remaining_words' => explode(',', $settings->free_plan)[0],
                'remaining_images' => explode(',', $settings->free_plan)[1] ?? 0,
                'password' => Hash::make(Str::random(12)),
                'affiliate_code' => Str::upper(Str::random(12)),
            ]);
            if($user){
                $this->createHubSpotContact($googleUser->getEmail(),$name,$surname);

            }
        }

        Auth::login($user);

        return redirect('/dashboard/user');
    }

    public function facebookCallback()
    {
        $facebookUser = Socialite::driver('facebook')->user();
        if($facebookUser->getEmail()){
            $checkUser = User::where('email', $facebookUser->getEmail())->exists();
            $settings = Setting::first();
    
            $nameParts = explode(' ', $facebookUser->getName());
            $name = $nameParts[0] ?? '';
            $surname = $nameParts[1] ?? '';
    
            if ($checkUser) {
                $user = User::where('email', $facebookUser->getEmail())->first();
                $user->facebook_token = $facebookUser->token;
                $user->avatar = $facebookUser->getAvatar();
                $user->affiliate_code = $user->affiliate_code ?? Str::upper(Str::random(12));
                $user->save();
            } else {
                $user = User::updateOrCreate([
                    'facebook_id' => $facebookUser->id,
                ], [
                    'name' => $name,
                    'surname' => $surname,
                    'email' => $facebookUser->getEmail(),
                    'facebook_token' => $facebookUser->token,
                    'avatar' => $facebookUser->getAvatar(),
                    'remaining_words' => explode(',', $settings->free_plan)[0],
                    'remaining_images' => explode(',', $settings->free_plan)[1] ?? 0,
                    'password' => Hash::make(Str::random(12)),
                    'affiliate_code' => Str::upper(Str::random(12)),
                ]);

                if($user){
                    $this->createHubSpotContact($facebookUser->getEmail(),$name,$surname);
    
                }
            }
    
            Auth::login($user);
        }
        return redirect('/dashboard/user');
        
    }

    public function handleHubSpotCallback(Request $request)
    {
        $clientId = config('services.hubspot.client_id');
        $clientSecret = config('services.hubspot.client_secret');
        $redirectUri = config('services.hubspot.redirect');

        $response = Http::asForm()->post('https://api.hubapi.com/oauth/v1/token', [
            'grant_type' => 'authorization_code',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri' => $redirectUri,
            'code' => $request->get('code'),
        ]);

        $hubspotUser = $response->json();

        // Now you have the HubSpot user information in $hubspotUser
        // Implement your logic to create or authenticate the user in your app

        return redirect('/dashboard/user');
    }

    public function redirectToHubSpot()
    {
        $clientId = config('services.hubspot.client_id');
        $redirectUri = config('services.hubspot.redirect');

        $authorizationUrl = "https://app.hubspot.com/oauth/authorize?client_id={$clientId}&redirect_uri={$redirectUri}&scope=oauth%20settings.users.write%20settings.users.read";
        
        return redirect($authorizationUrl);
    }
    public function login(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function registerCreate()
    {
        return view('panel.authentication.register');
    }

    public function registerStore(Request $request)
    {

      
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => ['required'], 'address'=>['required']

        ]);

        $affCode = null;
        if ($request->affiliate_code != null) {
            $affUser = User::where('affiliate_code', $request->affiliate_code)->first();
            if ($affUser != null) {
                $affCode = $affUser->id;
            }
        }

        //TODO DEMO
        if (env('APP_STATUS') == 'Demo') {
            $user = User::create([
                'name' => $request->name,
                'surname' => $request->surname,
                'email' => $request->email,
                'email_confirmation_code' => Str::random(67),
                'remaining_words' => 5000,
                'remaining_images' => 200,
                'password' => Hash::make($request->password),
                'email_verification_code' => Str::random(67),
                'affiliate_id' => $affCode,
                'affiliate_code' => Str::upper(Str::random(12)),
            ]);
        } else {
            $settings = Setting::first();

            $user = User::create([
                'name' => $request->name,
                'surname' => $request->surname,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'email_confirmation_code' => Str::random(67),
                'remaining_words' => explode(',', $settings->free_plan)[0],
                'remaining_images' => explode(',', $settings->free_plan)[1] ?? 0,
                'password' => Hash::make($request->password),
                'email_verification_code' => Str::random(67),
                'affiliate_id' => $affCode,
                'affiliate_code' => Str::upper(Str::random(12)),
            ]);

            if($user){
                $this->createHubSpotContact($request->email,$request->name,$request->surname,$request->phone,$request->address);
            }
          
        }


        //event(new Registered($user));

        dispatch(new SendConfirmationEmail($user));

        $settings = Setting::first();
        if ($settings->login_without_confirmation == 1) {
            Auth::login($user);
        } else {
            $data = array(
                'errors' => ['We have sent you an email for account confirmation. Please confirm your account to continue.'],
                'type' => 'confirmation',
            );
            return response()->json($data, 401);
        }


        return response()->json('OK', 200);
    }

    private function createHubSpotContact($email='', $firstname='', $lastname='', $phone='', $address='')
    {
        $requestData = [
            'email' => $email,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'phone' => $phone,
            'address' => $address,
            // Add other fields as needed
        ];
        
        $hubSpotData = [];
        
        foreach ($requestData as $property => $value) {
            $hubSpotData[] = [
                'property' => $property,
                'value' => $value,
            ];
        }

        $this->hubSpotService->createContact($hubSpotData);

    }

    public function PasswordResetCreate()
    {
        return view('panel.authentication.password_reset');
    }
}
