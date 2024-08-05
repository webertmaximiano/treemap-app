<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Collection;

use Inertia\Inertia;

use App\Models\TreeMap;
use App\Models\Country;
use App\Models\RegionCountry;
use App\Models\State;
use App\Models\Order;
use App\Models\Store;

use App\Services\TreeMapService;



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
        $orders = Order::all();
        
        $treeMap = $this->service->generateTreeMapData($orders);
        return inertia('TreeMap/Index', [
            'treeMap' => $treeMap,
        ]);
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
        $type= 'region';
        $identifier= 'Sudeste';
        $month = now()->format('Y-m');
        $data = $this->service->generateTreeMapReportLocale($type,  $identifier, $month);
       
        return Inertia::render('TreeMap/TreemapShow', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'treeMap' => $data,
        ]);
       // return inertia('TreeMapReport', ['treeMap' => $data]);

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
