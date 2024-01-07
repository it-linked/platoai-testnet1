<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class BraveSearchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if( $request->q )
        {
            $brave_result = $this->sent_brave_request( $request, "web" );
            // dd( $brave_result );
            $results = $brave_result;
            return view("panel.search.web",compact('results'));
        }else{
            return view('panel.search.index');
        }
    }

    /**
     * Display Image Search results
     */
    public function imageSearch(Request $request)
    {
        $brave_result = $this->sent_brave_request( $request, "images" );
        $results = $brave_result ?? [];
        return view("panel.search.images",compact('results'));
    }

    /**
     * Display Video Search results
     */
    public function videoSearch(Request $request)
    {
        $brave_result = $this->sent_brave_request( $request, "videos" );
        $results = $brave_result ?? [];
        return view("panel.search.videos",compact('results'));
    }
    
    /**
     * Display News Search results
     */
    public function newsSearch(Request $request)
    {
        $brave_result = $this->sent_brave_request( $request, "news" );
        // dd( $brave_result );
        $results = $brave_result ?? [];
        return view("panel.search.news",compact('results'));
    }

    private function sent_brave_request( $request, $type )
    {
        $keyword = $request->q;
        $offset = $request->offset;
        $url = "https://api.search.brave.com/res/v1/".$type."/search?q=". str_replace( "+", "%20", urlencode($keyword) );

        if( $offset )
        {
            $url .= "&offset=". $offset;
        }
        
        $api_key = "BSA2vpxtyu7FoVwRLWYgo0o1xUOia0X";
        $headers = [
            "Accept: application/json",
            "Accept-Encoding: gzip",
            "X-Subscription-Token: $api_key"
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");

        $apiResponse = json_decode(curl_exec($ch),true);
        
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }

        curl_close($ch);

        return $apiResponse;
    }

    /**
     * Brave search function. Call brave search API
     */
   
}
