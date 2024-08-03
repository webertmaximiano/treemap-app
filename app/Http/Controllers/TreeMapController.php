<?php

namespace App\Http\Controllers;

use App\Models\TreeMap;
use App\Models\Country;
use App\Models\RegionCountry;
use App\Models\State;
use App\Models\Order;
use App\Models\Store;


use App\Services\TreeMapService;

use Illuminate\Http\Request;

class TreeMapController extends Controller
{
    private $service;

    public function __construct(TreeMapService $treeMapService)
    {
        $this->service = $treeMapService;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show()
    {
        $type= 'state';
        $identifier= 'Acre';
        $month = now()->format('Y-m');
        $data = $this->service->generateTreeMapReportLocale($type,  $identifier, $month);
        // $country = Country::all();
        // $region = RegionCountry::all();
        // $stores = Store::all();
        // $states = State::all();
        // $orders = Order::all();

        dd($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TreeMap $treeMap)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TreeMap $treeMap)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TreeMap $treeMap)
    {
        //
    }
}
