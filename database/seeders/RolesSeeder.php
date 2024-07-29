<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       //create role "Employee" for users
        $roles = [
            'Client',
            'Admin',
            
        // Add more roles as needed
        ];

        foreach ($roles as $role) {
            $existed_role=Role::where('name' , $role)->first();
            if(!$existed_role){
                Role::create(['name' => $role]);
            }
        }
        $admin_role = Role::where('name','Admin')->first();
        

        $permissions = Permission::pluck('id', 'id')->all();

        $admin_role->syncPermissions($permissions);
        $user = User::create([
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'eldaimi@gmail.com',
            'username'=>'eldaimi',
            'phone' => null,
            'password' => Hash::make('gmadmin159!48@26#0'),
        ]);

        

        $user->assignRole([$admin_role->id]);

        
    }
}