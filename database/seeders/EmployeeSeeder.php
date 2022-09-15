<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::findOrFail(2);
        $employee = new Employee([
            'first_name' => 'Daniel',
            'last_name' => 'Diaz',
            'dni' => '34125698',
            'email' => $user->email,
            'phone' => '3764000000',
            'entrepreneurship_id' => 1,
            'user_id' => $user->id,
        ]);
        $employee->save();

        
        $user = User::findOrFail(3);
        $employee = new Employee([
            'first_name' => 'Tomas',
            'last_name' => 'Perez',
            'dni' => '33547846',
            'email' => $user->email,
            'phone' => '3743100000',
            'entrepreneurship_id' => 2,
            'user_id' => $user->id,
        ]);
        $employee->save();


        $user = User::findOrFail(4);
        $employee = new Employee([
            'first_name' => 'Angela',
            'last_name' => 'Rodriguez',
            'dni' => '25123456',
            'email' => $user->email,
            'phone' => '3764000001',
            'entrepreneurship_id' => 3,
            'user_id' => $user->id,
        ]);
        $employee->save();


        $user = User::findOrFail(5);
        $employee = new Employee([
            'first_name' => 'Claudia',
            'last_name' => 'Gonzalez',
            'dni' => '26321456',
            'email' => $user->email,
            'phone' => '3764000002',
            'entrepreneurship_id' => 4,
            'user_id' => $user->id,
        ]);
        $employee->save();


        Employee::factory(4)->create();
    }

}
