<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Item;

class ItemUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function want(){
        $itemCode = request()->itemCode;
        
        $client = new \RakutenRws_Client();
        $client->setApplicationId(env('RAKUTEN_APPLICATION_ID'));
        $rws_response = $client->execute('IchibaItemSearch', [
            'itemCode' => $itemCode,
        ]);
        $rws_item = $rws_response->getData()['Items'][0]['Item'];
        
        $item = Item::firstOrCreate([
            'code' => $rws_item['itemCode'],
            'name' => $rws_item['itemName'],
            'url' => $rws_item['itemUrl'],
            'image_url' => str_replace('?_ex=128x128', '', $rws_item['mediumImageUrls'][0]['imageUrl']),
        ]);
        
        \Auth::user()->want($item->id);
        
        return redirect()->back();
    }
    
    public function dont_want(){
        $itemCode = request()->itemCode;
        
        if (\Auth::user()->is_wanting($itemCode)) {
            $itemId = Item::where('code', $itemCode)->first()->id;
            \Auth::user()->dont_want($itemId);
        }
        return redirect()->back();
    }
    
    public function have(){
        $itemCode = request()->itemCode;
        
        $client = new \RakutenRws_Client();
        $client->setApplicationId(env('RAKUTEN_APPLICATION_ID'));
        $rws_response = $client->execute('IchibaItemSearch', [
            'itemCode' => $itemCode,
        ]);
        $rws_item = $rws_response->getData()['Items'][0]['Item'];
        
        $item = Item::firstOrCreate([
            'code' => $rws_item['itemCode'],
            'name' => $rws_item['itemName'],
            'url' => $rws_item['itemUrl'],
            'image_url' => str_replace('?_ex=128x128', '', $rws_item['mediumImageUrls'][0]['imageUrl']),
        ]);
        
        \Auth::user()->have($item->id);
        
        return redirect()->back();
    }
    
    public function dont_have(){
        $itemCode = request()->itemCode;
        
        if (\Auth::user()->is_having($itemCode)) {
            $itemId = Item::where('code', $itemCode)->first()->id;
            \Auth::user()->dont_have($itemId);
        }
        return redirect()->back();
    }
}