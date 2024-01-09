<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Exception;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{

    // function __construct()
    // {
    //     $this->middleware('permission:role|add-role|edit-role|delete-role', ['only' => ['index', 'store']]);
    //     $this->middleware('permission:add-role', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:edit-role', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:delete-role', ['only' => ['destroy']]);
    // }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $roles = Role::orderBy('id', 'DESC')->paginate(5);
        return view('admin.settings.role.all', compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $permission = Permission::get();
        return view('admin.settings.role.add', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);


        DB::beginTransaction();
        try {
            $role = Role::create(['name' => $request->input('name')]);

            $permission = Permission::find($request->input('permission'))->pluck('name');
            $role->syncPermissions($permission);
        } catch (Exception $e) {
            DB::rollback();
            // dd($e->getMessage());
            return redirect()->back()->with('status', $e->getMessage());
        }
        DB::commit();
        return redirect()->back()->with('status', 'Role created successfully');
    }
    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('admin.settings.role.view', compact('role', 'rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return view('admin.settings.role.edit', compact('role', 'permission', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */


    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'permission' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $role = Role::find($id);

            //Check if the role is not the superadmin role
            if ($role->name !== 'superadmin') {
                $permission = Permission::find($request->input('permission'))->pluck('name');
                $role->syncPermissions($permission);
                $role->save();
            } else {
                // Superadmin role not be updated
                throw new \Exception("You are not allowed to update permission the Superadmin role.");
            }
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('status', $e->getMessage());
        }

        DB::commit();
        return redirect()->back()->with('status', 'Role updated successfully');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        DB::beginTransaction();
        try {
            DB::table("roles")->where('id', $id)->delete();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('status', $e->getMessage());
        }
        DB::commit();
        return redirect()->back()->with('success', 'Role deleted successfully');
    }
}

