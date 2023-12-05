<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class BraveSearchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($slug = "search")
    {
        return view('panel.search.index',compact("slug"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Brave search function. Call brave search API
     */

    public function search(Request $request)
    {
        $keyword = $request->keyword;
        $type=$request->search_type;
        if($request->search_type=='search')
        $type="web";
        $url = "https://api.search.brave.com/res/v1/".$type."/search?q=$keyword";
    
        if($request->page>1){
            $url.="&offset=".$request->page;
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

       
        if($type=="images"){
            return view("panel.search.images",compact('apiResponse'));
        }
        else if($type=="web"){
            $apiResponse = $apiResponse['web'];
            return view("panel.search.web",compact('apiResponse'));
        }
        else if($type=="videos"){
           
            return view("panel.search.videos",compact('apiResponse'));

        }
        else {
            echo LaravelLocalization::setLocale();
        }
        echo json_encode($apiResponse);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
