<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderDataRequest;
use App\Http\Requests\UpdateOrderDataRequest;
use App\Models\OrderData;

class OrderDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         //
        return view('orderdata.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         //
        return view('orderdata.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderDataRequest $request)
    {
         //
        return view('orderdata.store');
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderData $orderData)
    {
         //
        return view('orderdata.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderData $orderData)
    {
         //
        return view('orderdata.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderDataRequest $request, OrderData $orderData)
    {
         //
        return view('orderdata.update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderData $orderData)
    {
         //
        return view('orderdata.destroy');
    }
}
