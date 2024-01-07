<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;


use App\Models\ZeusaiConversation;
use App\Models\ZeusaiInteraction;
use App\Models\Setting;

class ZeusAIController extends Controller
{
    
    public function index($id=null)
    {
        $settings = Setting::first();
        // Fetch the Site Settings object with openai_api_secret
        $apiKeys = explode(',', $settings->openai_api_secret);
        $apiKey = $apiKeys[array_rand($apiKeys)];

        $len = strlen($apiKey);
        $parts[] = substr($apiKey, 0, $l[] = rand(1, $len - 5));
        $parts[] = substr($apiKey, $l[0], $l[] = rand(1, $len - $l[0] - 3));
        $parts[] = substr($apiKey, array_sum($l));
        $apikeyPart1 = base64_encode($parts[0]);
        $apikeyPart2 = base64_encode($parts[1]);
        $apikeyPart3 = base64_encode($parts[2]);
        $apiUrl = base64_encode('https://api.openai.com/v1/chat/completions');
        $conversation = null;
        $interactions = null;
    
        // Check if $id is not null and retrieve the conversation and interactions
        if ($id !== null) {
            $conversation = ZeusaiConversation::with('interactions')->find($id);
            if ($conversation) {
                $interactions = $conversation->interactions;
            }
        }
    
        return view('panel.zeus_ai.index', compact('settings', 'apikeyPart1', 'apikeyPart2', 'apikeyPart3', 'apiUrl', 'conversation', 'interactions'));
    }

  
    
    public function getAllConversations(Request $request)
    {
        // $perPage = 20; // Number of conversations per page
    
        // Get today's conversations
        $todayConversations = ZeusaiConversation::orderBy('created_at','desc')->paginate(10);
    
        // if ($todayConversations->count() >= $perPage) {
        //     // If today's conversations are more than or equal to 20, paginate them
        //     $todayConversations = ZeusaiConversation::whereDate('created_at', Carbon::today())->paginate($perPage);
        // } else {
        //     // If today's conversations are not enough, get yesterday's conversations
        //     $yesterdayConversations = ZeusaiConversation::whereDate('created_at', Carbon::yesterday())->take($perPage - $todayConversations->count())->get();
    
        //     if ($yesterdayConversations->count() >= $perPage - $todayConversations->count()) {
        //         // If yesterday's conversations are more than or equal to remaining required conversations, paginate them
        //         $yesterdayConversations = ZeusaiConversation::whereDate('created_at', Carbon::yesterday())->paginate($perPage - $todayConversations->count());
        //     } else {
        //         // If yesterday's conversations are not enough, get last 7 days' conversations
        //         $last7DaysConversations = ZeusaiConversation::where('created_at', '>=', Carbon::now()->subDays(7))
        //             ->whereDate('created_at', '!=', Carbon::yesterday())
        //             ->whereDate('created_at', '!=', Carbon::today())
        //             ->take($perPage - $todayConversations->count() - $yesterdayConversations->count())
        //             ->get();
    
        //         if ($last7DaysConversations->count() >= $perPage - $todayConversations->count() - $yesterdayConversations->count()) {
        //             // If last 7 days' conversations are more than or equal to remaining required conversations, paginate them
        //             $last7DaysConversations = ZeusaiConversation::where('created_at', '>=', Carbon::now()->subDays(7))
        //                 ->whereDate('created_at', '!=', Carbon::yesterday())
        //                 ->whereDate('created_at', '!=', Carbon::today())
        //                 ->paginate($perPage - $todayConversations->count() - $yesterdayConversations->count());
        //         } else {
        //             // If last 7 days' conversations are not enough, get last 30 days' conversations
        //             $last30DaysConversations = ZeusaiConversation::where('created_at', '>=', Carbon::now()->subDays(30))
        //                 ->whereDate('created_at', '!=', Carbon::yesterday())
        //                 ->whereDate('created_at', '!=', Carbon::today())
        //                 ->take($perPage - $todayConversations->count() - $yesterdayConversations->count() - $last7DaysConversations->count())
        //                 ->get();
        //         }
        //     }
        // }
    
        // // Create an array to hold the results
        // $results = [
        //     'today' => $todayConversations,
        //     'yesterday' => $yesterdayConversations ?? [],
        //     'last7Days' => $last7DaysConversations ?? [],
        // ];
    
        // Return a JSON response
        return response()->json($todayConversations);
    }
    

    public function chatSave(Request $request)
    {
        $new = true;
        if($request->chat_id and $request->chat_id!=""){
        $chat = ZeusaiConversation::where('id', $request->chat_id)->first();
        $new = false;
        }
        else {
          
                // Create a new conversation if chat_id is not provided
                $chat = ZeusaiConversation::create([
                    'title' => "New Chat",
                    'user_id' => auth()->user()->id, // Assuming users are authenticated
                    'id' => Str::uuid(), // Add a unique string as required
                ]);
        }
      
        $message = new ZeusaiInteraction();
        $message->conversation_id = $chat->id;
        $message->user_message = $request->input;
        $message->chatgpt_response = $request->response;
        $message->credits = countWords($request->response);
        $message->words = countWords($request->response);
        $message->save();

        /**
         * @var \App\Models\User
         */
        $user = Auth::user();
 
        if ($user->remaining_words != -1) {
            $user->remaining_words -= $message->credits;
        }

        if ($user->remaining_words < -1) {
            $user->remaining_words = 0;
        }
        $user->save();

        return response()->json(['newConversation'=>$new,'title'=>$chat->title,'chat_id'=>$chat->id]);
    }
    public function updateConversation(Request $request, $id)
    {
        // Validate and update the conversation title based on the $request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        ZeusaiConversation::where('id', $id)->update(['title' => $validatedData['title']]);
        
        // Return a response as needed
        return response()->json(['message' => 'Conversation title updated successfully']);
    }

    public function deleteConversation($id)
    {
        // Find and delete the conversation based on the provided $id
        $conversation = ZeusaiConversation::findOrFail($id);

        // Delete all interactions associated with the conversation
        ZeusaiInteraction::where('conversation_id', $id)->delete();
    
        // Delete the conversation
        $conversation->delete();

        // Return a response as needed
        return response()->json(['message' => 'Conversation deleted successfully']);
    }
}
