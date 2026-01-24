<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Permissions\StorePermissionRequest;
use App\Http\Requests\Dashboard\Permissions\UpdatePermissionRequest;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    public function index()
    {
        $q = Permission::query();

        if (request('search')) {
            $q->where('name', 'like', '%' . request('search') . '%');
        }

        $rows = $q->latest()->paginate((int)request('per_page', 10))->withQueryString();

        return view('dashboard.permissions.index', compact('rows'));
    }

    public function create()
    {
        return view('dashboard.permissions.create');
    }

    public function store(StorePermissionRequest $request)
    {
        Permission::create($request->validated());
        return redirect()->route('dashboard.permissions.index')->with('success', 'Permission created');
    }

    public function edit($permission)
    {
        $row = Permission::findOrFail((int)$permission);
        return view('dashboard.permissions.edit', compact('row'));
    }

    public function update(UpdatePermissionRequest $request, $permission)
    {
        $row = Permission::findOrFail((int)$permission);
        $row->update($request->validated());

        return redirect()->route('dashboard.permissions.index')->with('success', 'Permission updated');
    }

    public function destroy($permission)
    {
        $row = Permission::findOrFail((int)$permission);
        $row->delete();

        return back()->with('success', 'Permission deleted');
    }
}
