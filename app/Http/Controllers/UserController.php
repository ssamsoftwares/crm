<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $data = User::orderBy('id', 'DESC');

        if ($request->has('search')) {
            $search = $request->input('search');
            $data->where(function ($subquery) use ($search) {
                $subquery->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', $search . '%')
                    ->orWhereHas('roles', function ($roleQuery) use ($search) {
                        $roleQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $data = $data->paginate(10);

        return view('user.all', compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // $roles = Role::pluck('name', 'name')->all();
        $roles = Role::whereNotIn('name', ['superadmin'])->pluck('name', 'name')->all();
        return view('user.add', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password'
        ]);

        DB::beginTransaction();

        try {
            $input = $request->all();
            $input['normal_password'] = $request->password;
            $input['password'] = Hash::make($input['password']);
            $user = User::create($input);
            // Assign the "user" role
            $user->assignRole('user');
            // $user->assignRole($request->roles);

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('status', $e->getMessage());
        }

        DB::commit();

        return redirect()->back()->with('status', 'User created successfully');
    }



    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $user = User::find($id);

        return view('user.view', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $user = User::find($id);
        // $roles = Role::pluck('name', 'name')->all();
        $roles = Role::whereNotIn('name', ['superadmin'])->pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('user.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            // 'roles' => 'required'
        ]);

        // dd($request->all());

        DB::beginTransaction();

        try {
            $input = $request->all();

            if (!empty($input['password'])) {
                $input['normal_password'] = $request->password;
                $input['password'] = Hash::make($input['password']);
            }else {
                $input = Arr::except($input, array('password'));
            }

            $user = User::find($id);
            $user->update($input);

            // Assign the "manager" role
            // $user->assignRole('manager');
            $user->assignRole($request->roles);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('status', $e->getMessage());
        }

        DB::commit();

        return redirect()->back()->with('status', 'User updated successfully');
    }


        // User status change
        public function userStatusUpdate($id)
        {
            $userBlock = User::find($id);
            $userBlock->status = $userBlock->status == 'active' ? 'block' : 'active';
            $userBlock->update();
            return redirect()->back()->with('status',  $userBlock->name . ' User status has been updated.');
        }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        DB::beginTransaction();
        try {
            User::find($id)->delete();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('status', $e->getMessage());
        }
        DB::commit();
        return redirect()->back()->with('status', 'User deleted successfully');
    }
}
