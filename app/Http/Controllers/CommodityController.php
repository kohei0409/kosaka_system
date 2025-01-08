<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommodityRequest;
use App\Http\Requests\UpdateCommodityRequest;
use App\Models\Commodity;

class CommodityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('commodity.index');

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('commodity.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommodityRequest $request)
    {
        //
        return view('commodity.store');
    }

    /**
     * Display the specified resource.
     */
    public function show(Commodity $commodity)
    {
        //
        return view('commodity.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Commodity $commodity)
    {
        //
        return view('commodity.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommodityRequest $request, Commodity $commodity)
    {
        //
        return view('commodity.update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commodity $commodity)
    {
        //
        return view('commodity.destroy');
    }
}
