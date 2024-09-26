<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:admin.index', ['only' => ['index']]);
        $this->middleware('permission:admin.create', ['only' => ['create']]);
        $this->middleware('permission:admin.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:admin.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::latest('id')->get();

            return DataTables::of($users)
                ->addColumn('DT_RowIndex', function ($users) {
                    return $users->id;
                })
                ->addColumn('roles', function ($users) {
                    return $users->roles->map(function ($role) {
                        return '<span class="inline-block bg-gray-200 text-gray-700 text-sm font-semibold px-3 py-1 rounded-full mr-2 border">'
                            . $role->name . '</span>';
                    })->implode(' ');
                })
                ->addColumn('action', function ($users) {
                    return '<a href="' . route('user.edit', $users->id) . '" class="btn_navy text-white px-2 rounded ms-3">Edit</a>' .
                        '<a href="' . route('user.destroy', $users->id) . '" class="btn_red text-white px-2 rounded delete ms-3">Delete</a>';
                })
                ->rawColumns(['roles', 'action'])
                ->make(true);
        }

        return view('admin.pages.user.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.user.create', ['roles' => Role::get()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email:rfc,dns'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'roles' => ['required', 'array'], // Make sure 'roles' is an array
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->syncRoles($request->roles);

        flash()->success('Your information has been saved.')->flash();
        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // deprecated
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admin.pages.user.edit', ['user' => User::find($id), 'roles' => Role::get()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'email' => ['required', 'email:rfc,dns'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()], // Password is nullable to allow optional updates
            'roles' => ['required', 'array'], // Validate roles as an array
        ]);

        $user = User::findOrFail($id); // Use findOrFail for better error handling

        // Update user data, check if password is provided, else keep the current password
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
        ]);

        // Sync multiple roles
        $user->syncRoles($request->roles);

        flash()->success('Your information has been updated.')->flash();
        return redirect()->route('user.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
    }
}
