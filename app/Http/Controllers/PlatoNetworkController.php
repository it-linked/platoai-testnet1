<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;
use App\Models\Setting;

class PlatoNetworkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($slug)
    { 
        $urls = [
            'plato-network' => 'https://platodata.network/',
            'amplifi' => 'https://amplifipr.com/',
            'plato-ainet' => 'https://platoaistream.net/',
            'plato-aistream' => 'https://platoaistream.com/',
            'plato-blockchain' => 'https://platoblockchain.com/',
            'platodata-io' => 'https://platodata.io/',
            'plato-esg' => 'https://platoesg.com/',
            'plato-gaming' => 'https://platogaming.com/',
            'plato-health' => 'https://platohealth.ai/',
            'plato-net' => 'https://platoblockchain.net/',
            'coingenius' => 'https://platoblockchain.net/',
            'xlera8' => 'https://xlera8.com/',
            'zephyrnet' => 'https://zephyrnet.com/',
        ];
    
        $url = $urls[$slug] ?? 'https://platodata.network/';
    
        return view('panel.platonetwork.index', compact('url'));
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
