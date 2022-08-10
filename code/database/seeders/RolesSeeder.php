<?php

namespace Database\Seeders;

use App\Dictionaries\UserRolesDictionary;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brandon = User::create([
            'name' => 'Brandon',
            'email' => 'brandon@cleancommit.io',
            'password' => Hash::make('password'),
        ]);
        $brandon->assignRole(UserRolesDictionary::ROLE_DIRECTOR);

        $warehouse = User::create([
            'name' => 'Darryl',
            'email' => 'derryl@cleancommit.io',
            'password' => Hash::make('password'),
        ]);
        $warehouse->assignRole(UserRolesDictionary::ROLE_WAREHOUSE);

        $installer_lukasz = User::create([
            'name' => 'Lukasz Installer',
            'email' => 'lukasz_installer@cleancommit.io',
            'password' => Hash::make('password'),
        ]);
        $installer_lukasz->assignRole(UserRolesDictionary::ROLE_INSTALLER);

        $installer_tim = User::create([
            'name' => 'Tim Installer',
            'email' => 'tim_installer@cleancommit.io',
            'password' => Hash::make('password'),
        ]);
        $installer_tim->assignRole(UserRolesDictionary::ROLE_INSTALLER);

        $installer_wk = User::create([
            'name' => 'Wojtek Installer',
            'email' => 'wk_installer@cleancommit.io',
            'password' => Hash::make('password'),
        ]);
        $installer_wk->assignRole(UserRolesDictionary::ROLE_INSTALLER);

        $installer_brandon = User::create([
            'name' => 'Brandon Installer',
            'email' => 'brandon_installer@cleancommit.io',
            'password' => Hash::make('password'),
        ]);
        $installer_brandon->assignRole(UserRolesDictionary::ROLE_INSTALLER);
    }
}
