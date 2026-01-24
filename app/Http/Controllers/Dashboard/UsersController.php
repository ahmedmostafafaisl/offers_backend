<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\Dashboard\UsersRepositoryInterface;
use App\Http\Requests\Dashboard\Users\StoreUserRequest;
use App\Http\Requests\Dashboard\Users\UpdateUserRequest;

class UsersController extends Controller
{
    public function __construct(private UsersRepositoryInterface $repo) {}

    public function index()
    {
        $rows = $this->repo->paginate([
            'search' => request('search'),
            'type' => request('type'),
        ], (int) request('per_page', 10));

        return view('dashboard.users.index', compact('rows'));
    }

    public function create()
    {
        $roles = Role::where('guard_name', 'web')->orderBy('name')->get();
        return view('dashboard.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // role لا تنحفظ في users table
        $roleName = $data['role'] ?? null;
        unset($data['role']);

        $user = User::create($data);

        // ✅ Assign role لو employee
        if ($user->type === 'employee') {
            $role = $roleName
                ? Role::where('name', $roleName)->where('guard_name', 'web')->first()
                : null;

            $user->syncRoles($role ? [$role->name] : ['viewer']); // default viewer
        } else {
            // اختياري: لو مش employee امنع roles
            $user->syncRoles([]);
        }

        return redirect()->route('dashboard.users.index')->with('success', 'User created');
    }

    public function edit($user)
    {
        $row = User::with('roles')->findOrFail((int)$user);
        $roles = Role::where('guard_name', 'web')->orderBy('name')->get();

        return view('dashboard.users.edit', compact('row', 'roles'));
    }

    public function update(UpdateUserRequest $request, $user)
    {
        $row = User::with('roles')->findOrFail((int)$user);
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $roleName = $data['role'] ?? null;
        unset($data['role']);

        $row->update($data);

        // ✅ Revoke old roles + assign new role
        if ($row->type === 'employee' && $roleName) {
            $role = $roleName
                ? Role::where('name', $roleName)->where('guard_name', 'web')->first()
                : null;

            $row->syncRoles($role ? [$role->name] : ['viewer']);
        } else {
            $row->syncRoles([]);
        }

        return redirect()->route('dashboard.users.index')->with('success', 'User updated');
    }

    public function destroy($user)
    {
        $row = User::findOrFail((int)$user);
        $row->syncRoles([]); // optional cleanup
        $row->delete();

        return back()->with('success', 'User deleted');
    }
}
