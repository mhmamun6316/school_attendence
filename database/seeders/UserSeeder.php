<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', 'mamun@gmail.com')->first();
        if (is_null($user)) {
            $user = new User();
            $user->name = "Mehedi Hassan";
            $user->email = "mamun@gmail.com";
            $user->organization_id = 1;
            $user->password = Hash::make('123456');
            $user->save();
        }
    }
}
