<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Cashflow;
use Illuminate\Database\Seeder;

class CashflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        //MOVIMIENTO DE CAJA CORRESPONDIENTES A UN CHECKOUT
        $booking = Booking::findOrFail(1);

        $cashflow = new Cashflow([
            'detail' => 'Adelanto de la reserva',
            'amount' => 400,
            'type' => 'Ingreso',
            'booking_id' => $booking->id,
            'entrepreneurship_id' => 1,
        ]);
        $cashflow->save();

        $cashflow = new Cashflow([
            'detail' => 'Servicio Opcional - Recorrido a caballo',
            'amount' => 800,
            'type' => 'Ingreso',
            'booking_id' => $booking->id,
            'entrepreneurship_id' => 1,
        ]);
        $cashflow->save();

        $cashflow = new Cashflow([
            'detail' => 'Checkout de reserva',
            'amount' => ($booking->amount + 800 - 400),
            'type' => 'Ingreso',
            'booking_id' => $booking->id,
            'entrepreneurship_id' => 1,
        ]);
        $cashflow->save();




        //MOVIMIENTO DE CAJA - VARIOS
        $cashflow = new Cashflow([
            'detail' => 'Compra de comida',
            'amount' => '5000',
            'type' => 'Egreso',
            'entrepreneurship_id' => 1,
        ]);
        $cashflow->save();

        $cashflow = new Cashflow([
            'detail' => 'Pago de servicios',
            'amount' => '20000',
            'type' => 'Egreso',
            'entrepreneurship_id' => 1,
        ]);
        $cashflow->save();

        $cashflow = new Cashflow([
            'detail' => 'Credito Externo',
            'amount' => '150000',
            'type' => 'Ingreso',
            'entrepreneurship_id' => 1,
        ]);
        $cashflow->save();


    }
}
