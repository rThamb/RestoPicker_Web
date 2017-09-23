<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
        [
            'email' => 'coderenation@gmail.com',
            'firstname' => 'Admin',
            'lastname' => 'A',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'postalCode' => 'H3Z3G4',
        ],
        [
            'email' => 'tricia@gmail.com',
            'firstname' => 'Tricia',
            'lastname' => 'Campbell',
            'username' => 'tricia',
            'password' => bcrypt('campbell'),
            'postalCode' => 'H3Z3G4',
        ],
        [
            'email' => 'jacobb@videotron.ca',
            'firstname' => 'Jacob',
            'lastname' => 'Brooker',
            'username' => 'ProRedPanda',
            'password' => bcrypt('123123'),
            'postalCode' => 'J6K2B9',
        ]
        ]);
    }
}
