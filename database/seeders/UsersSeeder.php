<?php

namespace Database\Seeders;

use App\Models\Entrepreneurship;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        
        //USER-ADMIN-MINISTERIO TURISMO
        $user = new User([
            'username' => 'ministerio1',
            'email' => 'ministerio@turismo.com',
            'password' => Hash::make('secret123'),
            'image_path' => 'assets/images/default.png',
        ]);
        $user->assignRole('admin');
        $user->save();
        

        /**
         * USERS-EMPRENDIMIENTOS
        **/
        $user = new User([
            'username' => 'emprendimiento1',
            'email' => 'emprendimiento1@turismo.com',
            'password' => Hash::make('secret123'),
            'image_path' => 'assets/images/default.png',
            'entrepreneurship_id' => 1,
        ]);
        $user->assignRole('client');
        $user->save();


        $user = new User([
            'username' => 'emprendimiento2',
            'email' => 'emprendimiento2@turismo.com',
            'password' => Hash::make('secret123'),
            'image_path' => 'assets/images/default.png',
            'entrepreneurship_id' => 2,
        ]);
        $user->assignRole('client');
        $user->save();


        $user = new User([
            'username' => 'emprendimiento3',
            'email' => 'emprendimiento3@turismo.com',
            'password' => Hash::make('secret123'),
            'image_path' => 'assets/images/default.png',
            'entrepreneurship_id' => 3,
        ]);
        $user->assignRole('client');
        $user->save();


        $user = new User([
            'username' => 'emprendimiento4',
            'email' => 'emprendimiento4@turismo.com',
            'password' => Hash::make('secret123'),
            'image_path' => 'assets/images/default.png',
            'entrepreneurship_id' => 4,
        ]);
        $user->assignRole('client');
        $user->save();

        
    }
}
