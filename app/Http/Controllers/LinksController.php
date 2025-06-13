<?php

namespace App\Http\Controllers;

use App\Models\Links;
use App\Http\Requests\StoreLinksRequest;
use App\Http\Requests\UpdateLinksRequest;

class LinksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLinksRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Links $links)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLinksRequest $request, Links $links)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Links $links)
    {
        //
    }
}
