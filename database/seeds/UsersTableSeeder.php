<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;
use \OTIFSolutions\ACLMenu\Models\UserRole;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::find(1) === null)
            User::Create(
                [
                    'id' => 1,
                    'name' => 'Administrator',
                    'email' => 'admin@system.com',
                    'password' => Hash::make('admin'),
                ]
            );
    }
}
