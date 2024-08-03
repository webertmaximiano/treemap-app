<?php

namespace App\Http\Controllers;

use App\Models\TreeMap;
use App\Models\State;

use Illuminate\Http\Request;

class TreeMapController extends Controller
{
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
        $states = State::with('regionCountry', 'regionCountry.country')->get();

        dd($states);
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
