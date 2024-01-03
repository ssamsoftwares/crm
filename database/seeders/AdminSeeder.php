<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadmin = User::create([
            'name' => 'SUPERADMIN',
            'email' => 'superadmin@gmail.com',
            'status' => 'active',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);

        $user = User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'status' => 'active',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'normal_password'=> 'password',
            'remember_token' => Str::random(10),
        ]);



        $role = Role::where('name','superadmin')->first();
        $permissions = Permission::pluck('id','id')->all();
        $role->syncPermissions($permissions);
        $superadmin->assignRole([$role->id]);


        $role = Role::where('name','user')->first();
        $permissions = Permission::pluck('id','id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);

        // $user_role = Role::where('name','user')->first();
        // $user_role->syncPermissions(['customers-list']);
        // $user->assignRole([$user_role->id]);

    }
}
