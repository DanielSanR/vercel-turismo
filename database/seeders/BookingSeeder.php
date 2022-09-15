<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Booking;
use App\Models\Extra;
use App\Models\Installation;
use App\Models\Observation;
use App\Models\OptionalService;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        //RESERVAS

        //agregar extra
        $installation = Installation::findOrFail(2);
        $client = Client::factory()->create();
        $guests = Client::factory(3)->create();

        $booking = new Booking([
            'phone_contact' => '1234123456',
            'adults' => 2,
            'minors' => 2,
            'date_from' => Carbon::now(),
            'date_to' => Carbon::now()->add(4,'day'),
            'checkin_date' => Carbon::now()->hour(9),
            'checkout_date' => Carbon::now()->add(4,'day')->hour(10),
            'amount' => 0,
            'checkin_employee_id' => 1,
            'checkout_employee_id' => 2,
            'client_id' => $client->id,
        ]);
        $booking->save();    

        //OBSERVACIONES
        Observation::create([
            'moment' => 'checkin',
            'description' => 'Una silla rota',
            'booking_id' => $booking->id,
        ]);
        Observation::create([
            'moment' => 'checkout',
            'description' => 'Faltan 2 toallas, puerta de armario rota',
            'booking_id' => $booking->id,
        ]);

        //HUESPEDES
        $guests->prepend($client);
        $booking->guests()->saveMany($guests);

        //DETALLE DE LA RESERVA
            //INSTALACIONES
        $quantityInstallations = 1;
        $booking->installations()->attach($installation->id, ['price_unit' => $installation->price, 'quantity' => $quantityInstallations]);
        /* $installation->quantity = $installation->quantity - $quantityInstallations;
        $installation->save(); */
        
            //EXTRAS
        $extras = Extra::factory(2)->create();
        foreach ($extras as $extra) {
            $booking->extras()->attach($extra->id, ['price_unit' => $extra->price]);
        }
        

        //CALCULAR MONTO TOTAL RESERVA
        $booking->amount = $this->calculeTotalAmount($booking, $installation, $quantityInstallations);
        $booking->save();



        //--------------------------------------------------------------------------------------------------------------------------//
        $installation = Installation::findOrFail(4);
        $client = Client::factory()->create();
        $guests = Client::factory(1)->create();

        $booking = new Booking([
            'phone_contact' => '3764125478',
            'adults' => 2,
            'minors' => 0,
            'date_from' => Carbon::now()->add(1,'day'),
            'date_to' => Carbon::now()->add(3,'day'),
            'checkin_date' => Carbon::now()->add(1,'day')->hour(9),
            'checkout_date' => Carbon::now()->add(3,'day')->hour(10),
            'amount' => 0,
            'checkin_employee_id' => 1,
            'checkout_employee_id' => 1,
            'client_id' => $client->id,
        ]);
        $booking->save();    

        //HUESPEDES
        $guests->prepend($client);
        $booking->guests()->saveMany($guests);

        //DETALLE DE LA RESERVA
            //INSTALACIONES
        $quantityInstallations = 1;
        $booking->installations()->attach($installation->id, ['price_unit' => $installation->price, 'quantity' => $quantityInstallations]);
        /* $installation->quantity = $installation->quantity - $quantityInstallations;
        $installation->save(); */

        //CALCULAR MONTO TOTAL RESERVA
        $booking->amount = $this->calculeTotalAmount($booking, $installation, $quantityInstallations);
        $booking->save();


 
        //--------------------------------------------------------------------------------------------------------------------------//
        $installation = Installation::findOrFail(5);
        $client = Client::factory()->create();
        $guests = Client::factory(2)->create();

        $booking = new Booking([
            'phone_contact' => '3764253698',
            'adults' => 2,
            'minors' => 1,
            'date_from' => Carbon::now()->add(3,'day'),
            'date_to' => Carbon::now()->add(5,'day'),
            'checkin_date' => Carbon::now()->add(3,'day')->hour(9),
            'checkout_date' => Carbon::now()->add(5,'day')->hour(10),
            'amount' => 0,
            'checkin_employee_id' => 2,
            'checkout_employee_id' => 1,
            'client_id' => $client->id,
        ]);
        $booking->save();    

        //HUESPEDES
        $guests->prepend($client);
        $booking->guests()->saveMany($guests);

        //DETALLE DE LA RESERVA
            //INSTALACIONES
        $quantityInstallations = 1;
        $booking->installations()->attach($installation->id, ['price_unit' => $installation->price, 'quantity' => $quantityInstallations]);
        /* $installation->quantity = $installation->quantity - $quantityInstallations;
        $installation->save(); */

        //CALCULAR MONTO TOTAL RESERVA
        $booking->amount = $this->calculeTotalAmount($booking, $installation, $quantityInstallations);
        $booking->save();

        

        //--------------------------------------------------------------------------------------------------------------------------//
        //agregar algun servicio

        $installation = Installation::findOrFail(6);
        $client = Client::factory()->create();
        $guests = Client::factory(3)->create();

        $booking = new Booking([
            'phone_contact' => '3764253698',
            'adults' => 4,
            'minors' => 0,
            'date_from' => Carbon::now()->add(2,'day'),
            'date_to' => Carbon::now()->add(4,'day'),
            'checkin_date' => Carbon::now()->add(2,'day')->hour(9),
            'checkout_date' => Carbon::now()->add(4,'day')->hour(10),
            'amount' => 0,
            'checkin_employee_id' => 2,
            'checkout_employee_id' => 1,
            'client_id' => $client->id,
        ]);
        $booking->save();    

        //HUESPEDES
        $guests->prepend($client);
        $booking->guests()->saveMany($guests);

        //DETALLE DE LA RESERVA
            //INSTALACIONES
            $quantityInstallations = 2;
            $booking->installations()->attach($installation->id, ['price_unit' => $installation->price, 'quantity' => $quantityInstallations]);

            
                //SERVICIO
            $optionalServices = OptionalService::find([1,2]);
            foreach ($optionalServices as $optionalService) {
                $booking->optionalServices()->attach($optionalService->id, ['price_unit' => $optionalService->price, 'quantity' => 1]);
            }
            
        //CALCULAR MONTO TOTAL RESERVA
        $booking->amount = $this->calculeTotalAmount($booking, $installation, $quantityInstallations);
        $booking->save();

        


        //--------------------------------------------------------------------------------------------------------------------------//
        //agregar algun servicio
        //agregar extra por rotura

        $installation = Installation::findOrFail(2);
        $client = Client::factory()->create();
        $guests = Client::factory(7)->create();

        $booking = new Booking([
            'phone_contact' => '3764547896',
            'adults' => 4,
            'minors' => 0,
            'date_from' => Carbon::now()->add(5, 'day'),
            'date_to' => Carbon::now()->add(8, 'day'),
            'checkin_date' => Carbon::now()->add(5, 'day')->hour(9),
            'checkout_date' => Carbon::now()->add(8, 'day')->hour(10),
            'amount' => 0,
            'checkin_employee_id' => 2,
            'checkout_employee_id' => 2,
            'client_id' => $client->id,
        ]);
        $booking->save();

        //OBSERVACIONES
        $observation = Observation::create([
            'moment' => 'checkin',
            'description' => 'Un espejo roto',
            'booking_id' => $booking->id,
        ]);

        //HUESPEDES
        $guests->prepend($client);
        $booking->guests()->saveMany($guests);

        //DETALLE DE LA RESERVA
            //INSTALACIONES
        $quantityInstallations = 2;
        $booking->installations()->attach($installation->id, ['price_unit' => $installation->price, 'quantity' => $quantityInstallations]);
        /* $installation->quantity = $installation->quantity - $quantityInstallations;
        $installation->save(); */

            //SERVICIO
        $optionalServices = OptionalService::find([1]);
        foreach ($optionalServices as $optionalService) {
            $booking->optionalServices()->attach($optionalService->id, ['price_unit' => $optionalService->price, 'quantity' => 2]);
        }
        
            //EXTRAS
        $extras = Extra::factory(1)->create();
        foreach ($extras as $extra) {
            $booking->extras()->attach($extra->id, ['price_unit' => $extra->price]);
        }
        

        //CALCULAR MONTO TOTAL RESERVA
        $booking->amount = $this->calculeTotalAmount($booking, $installation, $quantityInstallations);
        $booking->save();


        //--------------------------------------------------------------------------------------------------------------------------//
        $installation = Installation::findOrFail(4);
        $client = Client::factory()->create();
        $guests = Client::factory(1)->create();

        $booking = new Booking([
            'phone_contact' => '3764547896',
            'adults' => 2,
            'minors' => 0,
            'date_from' => Carbon::now()->add(7, 'day'),
            'date_to' => Carbon::now()->add(9, 'day'),
            'checkin_date' => Carbon::now()->add(7, 'day')->hour(9),
            'checkout_date' => Carbon::now()->add(9, 'day')->hour(10),
            'amount' => 0,
            'checkin_employee_id' => 2,
            'checkout_employee_id' => 2,
            'client_id' => $client->id,
        ]);
        $booking->save();

        //OBSERVACIONES

        //HUESPEDES
        $guests->prepend($client);
        $booking->guests()->saveMany($guests);

        //DETALLE DE LA RESERVA
            //INSTALACIONES
        $quantityInstallations = 1;
        $booking->installations()->attach($installation->id, ['price_unit' => $installation->price, 'quantity' => $quantityInstallations]);

            //SERVICIO
        
            //EXTRAS

        //CALCULAR MONTO TOTAL RESERVA
        $booking->amount = $this->calculeTotalAmount($booking, $installation, $quantityInstallations);
        $booking->save();

        

        //--------------------------------------------------------------------------------------------------------------------------//
        $installation = Installation::findOrFail(4);
        $client = Client::factory()->create();
        $guests = Client::factory(1)->create();

        $booking = new Booking([
            'phone_contact' => '3764547896',
            'adults' => 2,
            'minors' => 0,
            'date_from' => Carbon::now()->add(7, 'day'),
            'date_to' => Carbon::now()->add(9, 'day'),
            'checkin_date' => Carbon::now()->add(7, 'day')->hour(9),
            'checkout_date' => Carbon::now()->add(9, 'day')->hour(10),
            'amount' => 0,
            'checkin_employee_id' => 2,
            'checkout_employee_id' => 2,
            'client_id' => $client->id,
        ]);
        $booking->save();

        //OBSERVACIONES

        //HUESPEDES
        $guests->prepend($client);
        $booking->guests()->saveMany($guests);

        //DETALLE DE LA RESERVA
            //INSTALACIONES
        $quantityInstallations = 1;
        $booking->installations()->attach($installation->id, ['price_unit' => $installation->price, 'quantity' => $quantityInstallations]);

            //SERVICIO
        
            //EXTRAS

        //CALCULAR MONTO TOTAL RESERVA
        $booking->amount = $this->calculeTotalAmount($booking, $installation, $quantityInstallations);
        $booking->save();

        //--------------------------------------------------------------------------------------------------------------------------//

        $installation = Installation::findOrFail(4);
        $client = Client::factory()->create();
        $guests = Client::factory(1)->create();

        $booking = new Booking([
            'phone_contact' => '3764547896',
            'adults' => 2,
            'minors' => 0,
            'date_from' => Carbon::now()->add(7, 'day'),
            'date_to' => Carbon::now()->add(9, 'day'),
            'checkin_date' => Carbon::now()->add(7, 'day')->hour(9),
            'checkout_date' => Carbon::now()->add(9, 'day')->hour(10),
            'amount' => 0,
            'checkin_employee_id' => 2,
            'checkout_employee_id' => 2,
            'client_id' => $client->id,
        ]);
        $booking->save();

        //OBSERVACIONES

        //HUESPEDES
        $guests->prepend($client);
        $booking->guests()->saveMany($guests);

        //DETALLE DE LA RESERVA
            //INSTALACIONES
        $quantityInstallations = 1;
        $booking->installations()->attach($installation->id, ['price_unit' => $installation->price, 'quantity' => $quantityInstallations]);

            //SERVICIO
        
            //EXTRAS

        //CALCULAR MONTO TOTAL RESERVA
        $booking->amount = $this->calculeTotalAmount($booking, $installation, $quantityInstallations);
        $booking->save();

        //--------------------------------------------------------------------------------------------------------------------------//

    }   
    


    public function calculeTotalAmount($booking, $installation, $quantityInstallations) {

        $diffDays = Carbon::parse($booking->date_from)->diffInDays(Carbon::parse($booking->date_to)) + 1;
        $amountServices = 0;
        $amountExtras = 0;

        if ($booking->optionalServices()->count() > 0) {
            foreach ($booking->optionalServices as $optionalService) {
                $amountServices += ($optionalService->pivot->price_unit * $optionalService->pivot->quantity);
            }
        }
       
        if ($booking->extras()->count() > 0) {
            foreach ($booking->extras as $extra) {
                $amountExtras += $extra->pivot->price_unit;
            }
        }

        $amountInstallation = ($installation->price * $quantityInstallations) * $diffDays;
        $total = $amountInstallation + $amountServices + $amountExtras;
       
        return $total;
    }    


}
