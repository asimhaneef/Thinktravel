<?php

namespace App\Http\Controllers;

use App\Models\WebPages;
use App\Http\Requests\StoreWebPagesRequest;
use App\Http\Requests\UpdateWebPagesRequest;
use App\Mail\ContactusCreated;
use Illuminate\Support\Facades\Mail;

class WebPagesController extends Controller
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
    public function store(StoreWebPagesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(WebPages $webPages)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWebPagesRequest $request, WebPages $webPages)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WebPages $webPages)
    {
        //
    }
    public function emailContact(StoreWebPagesRequest $request)
    {
       $ownerEmail = env('MAIL_FROM_ADDRESS');  // Set the owner's email, or you can hardcode an email address here
        Mail::to($ownerEmail)->send(new ContactusCreated($request));
        
    }

    public function home()
    {
        return view('layout.spa');
    }
    public function about()
    {
        return view('layout.spa');
    }
    
    public function contact()
    {
        return view('layout.spa');
    }
    public function inquiry()
    {
        return view('layout.spa');
    }
    public function documents()
    {
        return view('layout.spa');
    }
    public function vacations()
    {
        return view('layout.spa');
    }
    public function cruises()
    {
        return view('layout.spa');
    }
    public function flights()
    {
        return view('layout.spa');
    }
}
