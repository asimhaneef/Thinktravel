<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class RoleService
{

    public function index()
    {
        try {
            $request = request();
            $query = Role::with('permissions'); // ✅ Eager-load permissions

            // Filtering
            if ($request->has('filters')) {
                $filters = json_decode($request->filters, true);
                if (is_array($filters)) {
                    foreach ($filters as $filter => $value) {
                        if (!empty($value['value'])) {
                            $query->where($filter, 'like', '%' . $value['value'] . '%');
                        }
                    }
                }
            }

            // Sorting
            if ($request->has('sortField') && $request->has('sortOrder')) {
                $order = in_array($request->sortOrder, [1, 'asc']) ? 'asc' : 'desc';
                $query->orderBy($request->sortField, $order);
            } else {
                $query->orderBy('created_at', 'desc');
            }

            // Handling request type
            if ($request->has('request_type')) {
                $roles = $query->get();
            } else {
                $roles = $query->paginate($request->rows ?? 10); // Default to 10 rows per page
            }

            return $roles;
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
      
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(data $data)
    {
        
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
