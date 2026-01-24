<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ reset cached roles/permissions
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // ✅ Guard name for dashboard
        $guard = 'web';

        // ✅ Modules in your dashboard
        $modules = [
            'dashboard',
            'users',
            'user_profiles',
            'roles',
            'permissions',
            'plans',
            'plan_features',
            'subscriptions',
            'categories',
            'offers',
            'offer_images',
            'offer_social_media',
            'user_social_media',
            'favorite_offers',
            'sliders',
            'offer_reports',
            'offer_complaints',
            'pending_profile_verifications',
            // ✅ add payments modules if you already created permissions for them
            'payments',
            'payment_items',
        ];

        // ✅ CRUD verbs
        $actions = ['view', 'create', 'update', 'delete'];

        $allPermissions = [];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $name = "{$module}.{$action}";
                $perm = Permission::firstOrCreate([
                    'name' => $name,
                    'guard_name' => $guard,
                ]);
                $allPermissions[] = $perm;
            }
        }

        // ✅ Roles
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => $guard]);
        $admin      = Role::firstOrCreate(['name' => 'admin', 'guard_name' => $guard]);
        $manager    = Role::firstOrCreate(['name' => 'manager', 'guard_name' => $guard]);
        $viewer     = Role::firstOrCreate(['name' => 'viewer', 'guard_name' => $guard]);

        // ✅ super_admin: كل الصلاحيات
        $superAdmin->syncPermissions($allPermissions);

        // ✅ admin: كل شيء ماعدا delete (زي ما كنت عامل)
        $adminPerms = Permission::where('guard_name', $guard)
            ->whereNotIn('name', collect($modules)->map(fn($m) => "{$m}.delete")->toArray())
            ->get();
        $admin->syncPermissions($adminPerms);

        // ✅ manager: view/create/update فقط
        $managerPerms = Permission::where('guard_name', $guard)
            ->where(function ($q) use ($modules) {
                foreach ($modules as $m) {
                    $q->orWhereIn('name', [
                        "{$m}.view",
                        "{$m}.create",
                        "{$m}.update",
                    ]);
                }
            })
            ->get();
        $manager->syncPermissions($managerPerms);

        // ✅ viewer: view فقط
        $viewerPerms = Permission::where('guard_name', $guard)
            ->where(function ($q) use ($modules) {
                foreach ($modules as $m) {
                    $q->orWhere('name', "{$m}.view");
                }
            })
            ->get();
        $viewer->syncPermissions($viewerPerms);

        /*
        |--------------------------------------------------------------------------
        | ✅ Dashboard Users (employees) with different roles
        |--------------------------------------------------------------------------
        | Password for all: Admin@12345
        */
        $defaultPassword = 'Admin@12345';

        $dashboardUsers = [
            [
                'email' => 'superadmin@dashboard.com',
                'name'  => 'Super Admin',
                'role'  => 'super_admin',
            ],
            [
                'email' => 'admin@dashboard.com',
                'name'  => 'Admin',
                'role'  => 'admin',
            ],
            [
                'email' => 'manager@dashboard.com',
                'name'  => 'Manager',
                'role'  => 'manager',
            ],
            [
                'email' => 'viewer@dashboard.com',
                'name'  => 'Viewer',
                'role'  => 'viewer',
            ],
        ];

        foreach ($dashboardUsers as $du) {
            $user = User::firstOrCreate(
                ['email' => $du['email']],
                [
                    'type' => 'employee',
                    'name' => $du['name'],
                    'password' => Hash::make($defaultPassword),
                    'email_verified_at' => now(),
                    'phone' => null,
                    'country' => 'Saudi Arabia',
                    'city' => 'Riyadh',
                ]
            );

            // ✅ ensure it's employee even if existed
            $user->update([
                'type' => 'employee',
                'name' => $du['name'],
                'email_verified_at' => $user->email_verified_at ?? now(),
            ]);

            // ✅ assign role (replace old roles)
            $user->syncRoles([$du['role']]);
        }
    }
}
