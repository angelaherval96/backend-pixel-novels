<?php

namespace App\Http\Controllers;

use App\Models\Reading;
use App\Http\Requests\StoreReadingRequest;
use App\Http\Requests\UpdateReadingRequest;

class ReadingController extends Controller
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
    public function store(StoreReadingRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Reading $reading)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reading $reading)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReadingRequest $request, Reading $reading)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reading $reading)
    {
        //
    }
}
