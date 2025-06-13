<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class TenantService
{

    public function index()
    {
        //
        $tenant = Tenant::all();
        return [
            'status' => true,
            'message' => config('constants.data_retrieved'),
            'data'=> $tenant
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(array $data)
    {
        try {
            $tenant = Tenant::create([
                'username' => $data['username'],
                'email' => $data['email'],
                'domain_name' => $data['domain_name'],
                'password' => Hash::make($data['password']), // Hashing password
            ]);

            $tenant->domains()->create([
                'domain' => $data['domain_name'].'.'.config('app.domain'),  
            ]);
           
            return [
                'status' => true,
                'message' => 'Tenant created successfully',
                'tenant' => $tenant,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Failed to create tenant',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(data $data)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(data $data)
    {
        //
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(data $data)
    {
        //
    }
}
