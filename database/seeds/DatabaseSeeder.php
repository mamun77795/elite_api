<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => '$2y$10$gI9a.PZb.5Mk935kPoy3cuM6aH7e7o6PZygOeUDrtPnLb7VgUmWwC',
            'status' => '1',
        ]);
//        DB::table('users')->insert([
//            'name' => '',
//            'email' => 'miliniam@gmail.com',
//            'password' => '$2y$10$gI9a.PZb.5Mk935kPoy3cuM6aH7e7o6PZygOeUDrtPnLb7VgUmWwC',
//            'status' => '1',
//        ]);
        DB::table('roles')->insert([
            'name' => 'admin',
            'display_name' => 'Admin',
            'description' => 'Admin User',
        ]);
        DB::table('roles')->insert([
            'name' => 'company',
            'display_name' => 'Company',
            'description' => 'Company User',
        ]);
        DB::table('roles')->insert([
            'name' => 'employees',
            'display_name' => 'Employees',
            'description' => 'Employees User',
        ]);
        DB::table('roles')->insert([
            'name' => 'moderator',
            'display_name' => 'Moderator',
            'description' => 'Moderator User',
        ]);
        DB::table('roles')->insert([
            'name' => 'viewer',
            'display_name' => 'viewer',
            'description' => 'viewer User',
        ]);
        DB::table('role_user')->insert([
            'user_id' => '1',
            'role_id' => '1',

        ]);
//        DB::table('role_user')->insert([
//            'user_id' => '2',
//            'role_id' => '2',
//
//        ]);
    }
}
