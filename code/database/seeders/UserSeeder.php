<?php

namespace Database\Seeders;

use App\Dictionaries\UserRolesDictionary;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        User::query()->truncate();
        Role::query()->delete();
        Schema::enableForeignKeyConstraints();

        $role_director = Role::create(['name' => UserRolesDictionary::ROLE_DIRECTOR]);
        $role_installer = Role::create(['name' => UserRolesDictionary::ROLE_INSTALLER]);
        $role_warehouse = Role::create(['name' => UserRolesDictionary::ROLE_WAREHOUSE]);

        $lukasz = User::create([
            'name' => 'Lucas Przezdziek',
            'email' => 'lukasz@cleancommit.io',
            'password' => Hash::make('password'),
        ]);
        $lukasz->assignRole([$role_director->id]);

        $tim = User::create([
            'name' => 'Tim Davidson',
            'email' => 'tim@cleancommit.io',
            'password' => Hash::make('password'),
        ]);
        $tim->assignRole([$role_director->id]);

        $wk = User::create([
            'name' => 'Wojciech Kałużny',
            'email' => 'wk@cleancommit.io',
            'password' => Hash::make('password'),
        ]);
        $wk->assignRole([$role_director->id]);
    }
}
