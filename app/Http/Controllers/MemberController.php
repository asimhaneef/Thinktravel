<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class MemberController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // new Middleware('permission:members.view', only: ['index']),
            new Middleware('permission:members.edit', only: ['edit', 'update']),
            new Middleware('permission:members.add', only: ['add', 'store']),
            new Middleware('permission:members.delete', only: ['destroy']),
        ];
    }
    protected static ?string $password;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $request = request();
        $query = Member::with('country');

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
        $resources = $query->paginate($request->rows);

        return response()->json($resources);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMemberRequest $request)
    {
        //
        Member::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_no' => $request->phone_no,
            'gender' => $request->gender,
            'email' => $request->email,
            'address' => $request->address,
            'postal_code' => $request->postal_code,
            'city' => $request->city,
            'country_id' => $request->country_id,
            'member_code' => $request->member_code,
            'user_name' => $request->user_name,
            'password' => static::$password ??= Hash::make('password')
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($member)
    {
        //
        return Member::where('id', $member)->first();
    }

    public function searchMember($phone_no)
    {
        //
        $member = Member::where('phone_no','like', '%'.$phone_no.'%')->first();
        // If the member is found, return the member data
        if ($member) {
            return response()->json($member, 200);
        }

        // If no member is found, return a 404 response
        // return response()->json(['message' => 'Member not found'], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMemberRequest $request, Member $member)
    {
        //
        $query = Member::find($request->id);
        $query->update([
            'full_name' => $request->full_name,
            'phone_no' => $request->phone_no,
            'email' => $request->email,
            'address' => $request->address,
            'postal_code' => $request->postal_code,
            'city' => $request->city,
            'country_id' => $request->country_id,
            'member_code' => $request->member_code,
            'user_id' => auth()->id()
        ]);

        return true;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        //
        $member->delete();
        return response()->json(null, 204);
    }
}
