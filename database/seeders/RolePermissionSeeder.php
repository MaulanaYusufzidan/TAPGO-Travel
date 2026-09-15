<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * PRD section 28 Database Requirements (roles, permissions) &
     * section 30 Authentication & Authorization (role Customer/
     * Operator/Admin, authorization via Middleware/Policies/
     * Permissions).
     */
    protected array $permissions = [
        ['name' => 'View Admin Dashboard', 'slug' => 'view-admin-dashboard'],
        ['name' => 'Manage Destinations', 'slug' => 'manage-destinations'],
        ['name' => 'Manage Trips', 'slug' => 'manage-trips'],
        ['name' => 'Manage Schedules', 'slug' => 'manage-schedules'],
        ['name' => 'Manage Bookings', 'slug' => 'manage-bookings'],
        ['name' => 'Manage Payments', 'slug' => 'manage-payments'],
    ];

    protected array $rolePermissions = [
        'admin' => [
            'view-admin-dashboard', 'manage-destinations', 'manage-trips',
            'manage-schedules', 'manage-bookings', 'manage-payments',
        ],
        'operator' => [
            'manage-trips', 'manage-schedules',
        ],
        'customer' => [],
    ];

    public function run(): void
    {
        foreach ($this->permissions as $permission) {
            Permission::updateOrCreate(['slug' => $permission['slug']], $permission);
        }

        foreach (['customer', 'operator', 'admin'] as $slug) {
            Role::updateOrCreate(['slug' => $slug], ['name' => ucfirst($slug)]);
        }

        foreach ($this->rolePermissions as $roleSlug => $permissionSlugs) {
            $role = Role::where('slug', $roleSlug)->first();
            $permissionIds = Permission::whereIn('slug', $permissionSlugs)->pluck('id');
            $role->permissions()->sync($permissionIds);
        }
    }
}
