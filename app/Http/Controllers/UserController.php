<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CodesList;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // new Middleware('permission:users.view', only: ['index']),
            new Middleware('permission:users.edit', only: ['edit', 'update']),
            new Middleware('permission:users.add', only: ['add', 'store']),
            new Middleware('permission:users.delete', only: ['destroy']),
        ];
    }
    protected static ?string $password;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $request = request();
        $query = User::query();

        // Include roles in the query
        $query->with('roles','manager','subordinates'); // Adjust columns as needed
        
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

        // Pagination
        $users = $query->paginate($request->rows);
        $current_user_id = auth()->id() ?? null;
        $data = [
            'current_user_id' => $current_user_id,
            'users' => $users
        ];
        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email', // Ensure email is unique            '
            'username' => 'required|unique:users,username',
        ]);
        $validatedData['phone'] = $request->phone;
        // $validatedData['role_id'] = $request->roles;
        $validatedData['manager_id'] = $request->manager_id;
        $validatedData['display_onlist'] =  $request->display_onlist;
        $validatedData['is_active'] =   $request->is_active;
        // Hash the password before storing it

        $validatedData['password'] = static::$password ??= Hash::make('password'); //bcrypt($validatedData['password']);
        // Create and save the new user
        $user = User::create($validatedData);
        $user->syncRoles($request->role);
        // $Role = Role::where('id', $request->roles)->first();
        // $user->assignRole($Role->name);
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

        $dob = Carbon::parse($request->date_of_birth);
        $user = User::find($request->id);
        $user->update([
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'manager_id' => $request->manager_id,
            'display_onlist' => $request->display_onlist,
            'is_active' => $request->is_active,
        ]);
        // Assign the role to the user
        // $role = Role::findOrFail($request->roles);
        // $user->syncRoles([$role->name]);
        $user->syncRoles($request->role);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
        $user->delete();
        return response()->json(null, 204);
    }
    public function checkFirstLogin(Request $request)
    {
        $user = auth()->user();    
        // Check if it's the user's first login
        if ($user->is_first_login) {
            return response()->json(['redirect' => true, 'route' => 'change-password']);
        }    
        return response()->json(['redirect' => false]);
    }
    public function updatePassword(Request $request)
    {
        $validatedData = $request->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|min:8',
        ]);

        $user = User::find(auth()->id());
        // Check if the current password matches the hashed password in the database
        if (!Hash::check($validatedData['currentPassword'], $user->password)) {
            // If current password does not match, return an error response
            return response()->json([
                'currentPassword' => 'Current password is incorrect',
            ], 422); // Unprocessable Entity HTTP status code
        }

        $user->update([
            'password' => Hash::make($validatedData['newPassword']),
            'is_first_login' => 0,
        ]);

        return response()->json([
            'message' => 'Password updated successfully',
        ], 200); // OK HTTP status code
    }
    public function getRoles(Request $request)
    {
        $roles = Role::all();
        return response()->json(['roles' => $roles], 200);
    }
    public function getAgents(){
        $agent = User::whereHas('roles', function ($query) {
            $query->where('name', 'AGENT'); // Assuming the role name is stored in a 'name' column
        })->get();

        $current_user_id = auth()->id() ?? null;
        // Include roles in the query
        return response()->json(['agents' => $agent,
            'current_user_id' => $current_user_id,], 200); 
    }
    public function getSuppliers(){
        $supplier = CodesList::query()->get();

        // Include roles in the query
        return response()->json(['suppliers' => $supplier], 200);
    }
    public function impersonate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if (!auth()->user()->canImpersonate()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        auth()->user()->impersonate($user);

        return response()->json(['message' => 'Now impersonating user', 'redirect' => url('/')]);
    }

    public function stopImpersonate()
    {
        if (!auth()->user()->isImpersonated()) {
            return response()->json(['error' => 'Not impersonating'], 400);
        }

        auth()->user()->leaveImpersonation();

        return redirect('/dashboard')->with('message', 'Stopped impersonating');
    }
}
