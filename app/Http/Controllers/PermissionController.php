<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // new Middleware('permission:permissions.view', only: ['index']),
            new Middleware('permission:permissions.edit', only: ['edit', 'update']),
            new Middleware('permission:permissions.add', only: ['add', 'store']),
            new Middleware('permission:permissions.delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $request = request();
        $query = Permission::query();

        // Filtering
        if ($request->has('filters')) {
            $filters = json_decode($request->filters, true);
            foreach ($filters as $filter => $value) {
                if (!empty($value['value'])) {
                    $query->where($filter, 'like', '%' . $value['value'] . '%');
                }
            }
        }

        // Sorting
        if ($request->has('sortField') && $request->has('sortOrder')) {
            if ($request['sortOrder'] == 1) {
                $order = 'asc';
            } else {
                $order = 'desc';
            }
            $query->orderBy($request['sortField'], $order);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        //if its a simple request

        if ($request->has('request_type')) {
            $Permissions = $query->get();
        } else {
            // Pagination
            $Permissions = $query->paginate($request->rows);
        }

        return response()->json($Permissions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'module_name' => 'required|string|max:255',
            'permission_name' => 'required|array|min:1', // Ensure it's an array
            'permission_name.*.name' => 'required|string|max:255', // Validate each permission name
        ]);
    
        $permissions = [];
    
        foreach ($validated['permission_name'] as $permission) {
            // Format permission name
            $fullPermissionName = strtolower(str_replace(' ', '-', $validated['module_name'])) . '.' . strtolower(str_replace(' ', '-', $permission['name']));
    
            // Create the permission if it doesn't exist
            $permissions[] = Permission::firstOrCreate(
                ['name' => $fullPermissionName, 'guard_name' => 'web']
            );
        }
    
        return response()->json(['message' => 'Permissions created successfully', 'permissions' => $permissions], 201);
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return response()->json(null, 204);
    }
    //show all tables
    public function getTables()
    {
        $excludedTables = ['roles_has_permissions','cache','cache_locks','chat_messages','failed_jobs','job_batches','job_failed','jobs','migrations','model_has_permissions','model_has_roles','password_resets','personal_access_tokens','permissions','role_has_permissions','password_reset_tokens','sessions','domains','tenants','attendance']; // Add tables you want to exclude
        $tables = DB::select('SHOW TABLES');
        $tableList = [];

        foreach ($tables as $table) {
            $tableName = reset($table);
            
            // Skip tables that should be excluded
            if (in_array($tableName, $excludedTables)) {
                continue;
            }

            $tableList[] = [
                'module_name' => ucfirst(str_replace('_', ' ', $tableName)), // Format name nicely
                'modulecode' => strtoupper($tableName) // Use uppercase table name as code
            ];
        }

        return response()->json($tableList);
    }
    //
    public function getPermissions()
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0]; // Extracts module name from permission
        });

        return response()->json($permissions);
    }
    //
    // public function userPermissions()
    // {
    //     $user = auth()->user();
    //     $permissions = $user->getAllPermissions()->pluck('name');

    //     return response()->json([
    //         'permissions' => $permissions, // 👈 wrap in 'permissions' key
    //     ]);
    // }

    public function userPermissions()
    {
        $user = auth()->user();

        if ($user->hasRole('Super Admin')) {
            // Give all permissions if user is Super Admin
            $permissions = \Spatie\Permission\Models\Permission::pluck('name');
        } else {
            // Otherwise return only assigned permissions
            $permissions = $user->getAllPermissions()->pluck('name');
        }

        return response()->json([
            'permissions' => $permissions,
        ]);
    }



}
