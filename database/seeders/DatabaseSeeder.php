<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            EntrepreneurshipSeeder::class,
            UsersSeeder::class,
            WorkdaySeeder::class,
            EmployeeSeeder::class,
            LocalServiceSeeder::class,
            OptionalServiceSeeder::class,
            InstallationSeeder::class,
            ClientSeeder::class,
            BookingSeeder::class,
            CashflowSeeder::class,
        ]);
    }
}
