<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Roles\StoreRoleRequest;
use App\Http\Requests\Dashboard\Roles\UpdateRoleRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    public function index()
    {
        $q = Role::query();

        if (request('search')) {
            $q->where('name', 'like', '%' . request('search') . '%');
        }

        $rows = $q->latest()->paginate((int)request('per_page', 10))->withQueryString();

        return view('dashboard.roles.index', compact('rows'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('name')->get();
        return view('dashboard.roles.create', compact('permissions'));
    }

    public function store(StoreRoleRequest $request)
    {
        $data = $request->validated();
        $role = Role::create(['name' => $data['name']]);

        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('dashboard.roles.index')->with('success', 'Role created');
    }

    public function edit($role)
    {
        $row = Role::findOrFail((int)$role);
        $permissions = Permission::orderBy('name')->get();
        $selected = $row->permissions()->pluck('name')->toArray();

        return view('dashboard.roles.edit', compact('row', 'permissions', 'selected'));
    }

    public function update(UpdateRoleRequest $request, $role)
    {
        $row = Role::findOrFail((int)$role);
        $data = $request->validated();

        $row->update(['name' => $data['name']]);
        $row->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('dashboard.roles.index')->with('success', 'Role updated');
    }

    public function destroy($role)
    {
        $row = Role::findOrFail((int)$role);
        $row->delete();

        return back()->with('success', 'Role deleted');
    }
}
