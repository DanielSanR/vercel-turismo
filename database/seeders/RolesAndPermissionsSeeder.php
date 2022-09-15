<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $adminRole = Role::create(['name' => 'admin']);
        $clientRole = Role::create(['name' => 'client']);

        $generalPermissions = [
            'index-entrepreneurship',
            'show-entrepreneurship',
            'store-entrepreneurship',
            'update-entrepreneurship',
            'delete-entrepreneurship',
            
            'index-user',
            'show-user',
            'store-user',
            'update-user',
            'delete-user',
            
            'index-workday',
            'store-workday',
            'update-workday',
            'delete-workday',
            
            'index-employee',
            'show-employee',
            'store-employee',
            'update-employee',
            'delete-employee',
            
            'index-installation',
            'show-installation',
            'store-installation',
            'update-installation',
            'delete-installation',
            
            'index-localservice',
            'store-localservice',
            'update-localservice',
            'delete-localservice',

            'index-optionalservice',        
            'store-optionalservice',        
            'update-optionalservice',       
            'delete-optionalservice',     

            'index-client',
            'show-client',
            'store-client',
            'update-client',
            'delete-client',
            'searchByDni-client',

            'index-cashflow',
            'show-cashflow',
            'store-cashflow',
            'update-cashflow',
            'delete-cashflow',
            'daily-cashflow',
            'weekly-cashflow',
            'monthly-cashflow',
            'historic-cashflow',

            'index-extra',
            'show-extra',
            'store-extra',
            'update-extra',
            'delete-extra',

            'index-observation',
            'store-observation',
            'update-observation',
            'delete-observation',

            'index-bookingAccommodation',
            'show-bookingAccommodation',
            'detailBooking-bookingAccommodation',
            'searchBookingByClient-bookingAccommodation',
            'availability-bookingAccommodation',
            'storeBooking-bookingAccommodation',
            'destroy-bookingAccommodation',
            'updateBooking-bookingAccommodation',
            'checkin-bookingAccommodation',
            'checkout-bookingAccommodation',
            'bookingsWithoutCheckout-bookingAccommodation',

            'addOptionalServicesDetail-bookingDetail',
            'removeOptionalServicesDetail-bookingDetail',
            'updateOptionalServicesQuantityDetail-bookingDetail',

            'getBookingsByDate-bookingWithoutAccommodation',     
            'show-bookingWithoutAccommodation',                        
            'detailBooking-bookingWithoutAccommodation',  
            'store-bookingWithoutAccommodation',   
            'checkout-bookingWithoutAccommodation',
            'update-bookingWithoutAccommodation',    
            'delete-bookingWithoutAccommodation',
            'bookingsWithoutCheckout-bookingWithoutAccommodation'
            
        ];

        $adminPermissions = [
            'index-entrepreneurship',
            'show-entrepreneurship',
            'store-entrepreneurship',
            'update-entrepreneurship',
            'delete-entrepreneurship',
            
            'index-user',
            'show-user',
            'store-user',
            'update-user',
            'delete-user',
            
            'index-workday',
            'index-installation',

            'index-employee',
            'show-employee',
            'store-employee',
            'update-employee',
            'delete-employee',
        ];

        $clientPermissions = [
            'show-entrepreneurship',
            'update-entrepreneurship',
            
            'show-user',
            'update-user',
            
            'index-workday',
            'store-workday',
            'update-workday',
            'delete-workday',
            
            'index-employee',
            'show-employee',
            'store-employee',
            'update-employee',
            'delete-employee',
            
            'index-installation', 
            'show-installation',                        
            'store-installation',                       
            'update-installation',                      
            'delete-installation',                      
            
            'index-localservice',
            'store-localservice',
            'update-localservice',
            'delete-localservice',

            'index-optionalservice',
            'store-optionalservice',
            'update-optionalservice',
            'delete-optionalservice',

            'index-client',
            'show-client',
            'store-client',
            'update-client',
            'delete-client',
            'searchByDni-client',

            'index-cashflow',
            'show-cashflow',
            'store-cashflow',
            'update-cashflow',
            'delete-cashflow',
            'daily-cashflow',
            'weekly-cashflow',
            'monthly-cashflow',
            'historic-cashflow',

            'index-extra',
            'show-extra',
            'store-extra',
            'update-extra',
            'delete-extra',

            'index-observation',
            'store-observation',
            'update-observation',
            'delete-observation',

            'index-bookingAccommodation',
            'show-bookingAccommodation',
            'detailBooking-bookingAccommodation',
            'searchBookingByClient-bookingAccommodation',
            'availability-bookingAccommodation',
            'storeBooking-bookingAccommodation',
            'destroy-bookingAccommodation',
            'updateBooking-bookingAccommodation',
            'checkin-bookingAccommodation',
            'checkout-bookingAccommodation',
            'bookingsWithoutCheckout-bookingAccommodation',

            'addOptionalServicesDetail-bookingDetail',
            'removeOptionalServicesDetail-bookingDetail',
            'updateOptionalServicesQuantityDetail-bookingDetail',

            'getBookingsByDate-bookingWithoutAccommodation',     
            'show-bookingWithoutAccommodation',                        
            'detailBooking-bookingWithoutAccommodation',  
            'store-bookingWithoutAccommodation',   
            'checkout-bookingWithoutAccommodation',
            'update-bookingWithoutAccommodation',    
            'delete-bookingWithoutAccommodation',
            'bookingsWithoutCheckout-bookingWithoutAccommodation'      

        ];



        foreach ($generalPermissions as $generalPermission) {
            Permission::create(['name' => $generalPermission]);
        }

        

        foreach ($adminPermissions as $adminPermission) {
            $adminRole->givePermissionTo($adminPermission);
        }

        foreach ($clientPermissions as $clientPermission) {
            $clientRole->givePermissionTo($clientPermission);
        }


    }
}
