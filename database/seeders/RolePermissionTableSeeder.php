<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                "name" => "superadmin"
            ],
            [
                "name" => "user"
            ]
        ];

        $permissions = [
            ["name" => "user-list"],
            ["name" => "user-view"],
            ["name" => "user-create"],
            ["name" => "user-edit"],
            ["name" => "user-delete"],
            ["name" => "customer-list"],
            ["name" => "customer-view"],
            ["name" => "customer-create"],
            ["name" => "customer-edit"],
            ["name" => "customer-delete"],
            ["name" => "comment-add"],
            ["name" => "comment-edit"],
            ["name" => "project-details-list"],
            ["name" => "project-details-edit"]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
