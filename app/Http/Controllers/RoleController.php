<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\RoleService;
use App\Http\Requests\StoreRoleRequest;


class RoleController extends Controller
{
    public $RoleService;

    public function __construct(RoleService $RoleService)
    {
        $this->RoleService = $RoleService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = $this->RoleService->index();
        
        return response()->json($roles);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        try {
            $id = $request->input('id') ?? null;
            // Generate role name: departmentName_roleName
            $roleName = strtoupper($request->input('name'));

            // Create Role
            if ($id) {
                $role = Role::findOrFail($id);
                $role->update([
                    'name' => $roleName,
                    'department_id' => $request->input('department_name.code'),
                ]);
                $message = 'Role updated successfully';
            } else {
                $role = Role::create([
                    'name' => $roleName,
                    'department_id' => $request->input('department_name.code'), // Access nested array properly
                    'guard_name' => 'web'
                ]);
            }

            // Assign Permissions (if provided)
            if ($request->has('permissions') && is_array($request->permissions)) {
                $role->syncPermissions($request->permissions);
            }

            return response()->json([
                'message' => 'Role created successfully',
                'role' => $role
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $role = Role::findOrFail($id);
            return response()->json($role);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Role not found', 'message' => $e->getMessage()], 404);
        }
    }

    //

    public function department_roles(Request $request)
    {
        $department_id = $request->input('department_id');

        if (!$department_id) {
            return response()->json(['error' => 'Department ID is required'], 400);
        }

        $roles = Role::where('department_id', $department_id)
                    ->where('name', '!=', 'Super Admin') // Exclude "Super Admin" role by name
                    ->get();

        return response()->json(['data' => $roles], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $role = Role::findOrFail($id);

            // Validate request
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:3|unique:roles,name,' . $role->id,
                'permissions' => 'array'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Update Role
            $role->update(['name' => $request->name]);

            // Sync Permissions (if provided)
            if (!empty($request->permissions) && is_array($request->permissions)) {
                $role->syncPermissions($request->permissions);
            }

            return response()->json(['message' => 'Role updated successfully', 'role' => $role], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();
            return response()->json(['message' => 'Role deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }
}