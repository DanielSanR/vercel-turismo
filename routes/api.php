<?php

use GuzzleHttp\Middleware;
use App\Models\Installation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExtraController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\WorkdayController;
use App\Http\Controllers\CashflowController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ObservationController;
use App\Http\Controllers\InstallationController;
use App\Http\Controllers\LocalServiceController;
use App\Http\Controllers\DetailBookingController;
use App\Http\Controllers\OptionalServiceController;
use App\Http\Controllers\EntrepreneurshipController;
use App\Http\Controllers\API\AuthenticationController;
use App\Http\Controllers\BookingAccommodationController;
use App\Http\Controllers\BookingWithoutAccommodationController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */


/**
 *  ROUTES sin token
 */
Route::post('login', [AuthenticationController::class,'login']);

Route::get('test', function () {
    return 'Hello World';
});

/**
 *  ROUTES con token
 */
Route::group(['middleware' => ['auth:sanctum']], function() {

    Route::post('logout', [AuthenticationController::class,'logout']);

    /**
     * Entrepreneurships / Emprendimientos
     */
    Route::get('emprendimientos', [EntrepreneurshipController::class,'index'])->middleware(['role:admin','permission:index-entrepreneurship']);
    Route::get('emprendimientos/{id}', [EntrepreneurshipController::class,'show'])->middleware(['role:admin|client','permission:show-entrepreneurship']);
    Route::post('emprendimientos', [EntrepreneurshipController::class,'store'])->middleware(['role:admin','permission:store-entrepreneurship']);
    Route::put('emprendimientos/{entrepeneurship}', [EntrepreneurshipController::class,'update'])->middleware(['role:admin|client','permission:update-entrepreneurship']);
    Route::delete('emprendimientos/{id}', [EntrepreneurshipController::class,'destroy'])->middleware(['role:admin','permission:delete-entrepreneurship']);

    /**
     * Users / usuarios del sistema
     */
    Route::get('usuarios', [UserController::class,'index'])->middleware(['role:admin','permission:index-user']);
    Route::get('usuarios/{id}', [UserController::class,'show'])->middleware(['role:admin|client','permission:show-user']);
    Route::post('usuarios', [UserController::class,'store'])->middleware(['role:admin','permission:store-user']);
    Route::put('usuarios/{user}', [UserController::class,'update'])->middleware(['role:admin|client','permission:update-user']);
    Route::delete('usuarios/{id}', [UserController::class,'destroy'])->middleware(['role:admin','permission:delete-user']);

    /**
     * Workdays / Días Laborales
     */
    Route::get('dias_laborales/emprendimiento/{id}', [WorkdayController::class,'index'])->middleware(['role:admin|client','permission:index-workday']);
    Route::post('dias_laborales', [WorkdayController::class,'store'])->middleware(['role:client','permission:store-workday']);
    Route::put('dias_laborales/{workday}', [WorkdayController::class,'update'])->middleware(['role:client','permission:update-workday']);
    Route::delete('dias_laborales/{id}', [WorkdayController::class,'destroy'])->middleware(['role:client','permission:delete-workday']);


    /**
     * Employee / Empleados
     */
    Route::get('empleados/emprendimiento/{id}', [EmployeeController::class,'index'])->middleware(['role:admin|client','permission:index-employee']);    
    Route::get('empleados/{id}', [EmployeeController::class,'show'])->middleware(['role:client','permission:show-employee']);    
    Route::post('empleados', [EmployeeController::class,'store'])->middleware(['role:client','permission:store-employee']);
    Route::put('empleados/{employee}', [EmployeeController::class,'update'])->middleware(['role:client','permission:update-employee']);
    Route::delete('empleados/{id}', [EmployeeController::class,'destroy'])->middleware(['role:client','permission:delete-employee']);


    /**
     * Installation / Instalacion
     */
    Route::get('instalaciones/emprendimiento/{id}', [InstallationController::class,'index'])->middleware(['role:admin|client','permission:index-installation']);
    Route::get('instalaciones/{id}', [InstallationController::class,'show'])->middleware(['role:client','permission:show-installation']);                       
    Route::post('instalaciones', [InstallationController::class,'store'])->middleware(['role:client','permission:store-installation']);                          
    Route::put('instalaciones/{installation}', [InstallationController::class,'update'])->middleware(['role:client','permission:update-installation']);         
    Route::delete('instalaciones/{id}', [InstallationController::class,'destroy'])->middleware(['role:client','permission:delete-installation']);                 


    /**
     * LocalService / Servicios locales
     */
    Route::get('servicios_locales/emprendimiento/{id}', [LocalServiceController::class,'index'])->middleware(['role:client','permission:index-localservice']);
    Route::post('servicios_locales/emprendimiento/{id}', [LocalServiceController::class,'store'])->middleware(['role:client','permission:store-localservice']);
    Route::put('servicios_locales/{localService}/emprendimiento/{entrepreneurship_id}', [LocalServiceController::class,'update'])->middleware(['role:client','permission:update-localservice']);
    Route::delete('servicios_locales/{id}/emprendimiento/{entrepreneurship_id}', [LocalServiceController::class,'destroy'])->middleware(['role:client','permission:delete-localservice']);


    /**
     * OptionalService / Servicios opcionales
     */
    Route::get('servicios_opcionales/emprendimiento/{id}', [OptionalServiceController::class,'index'])->middleware(['role:client','permission:index-optionalservice']);
    Route::post('servicios_opcionales/emprendimiento/{id}', [OptionalServiceController::class,'store'])->middleware(['role:client','permission:store-optionalservice']);
    Route::put('servicios_opcionales/{optionalService}/emprendimiento/{entrepreneurship_id}', [OptionalServiceController::class,'update'])->middleware(['role:client','permission:update-optionalservice']);
    Route::delete('servicios_opcionales/{id}/emprendimiento/{entrepreneurship_id}', [OptionalServiceController::class,'destroy'])->middleware(['role:client','permission:delete-optionalservice']);


    /**
     * Client / Clientes
     */
    Route::get('clientes/emprendimiento/{id}', [ClientController::class,'index'])->middleware(['role:client','permission:index-client']);
    Route::get('clientes/{id}/emprendimiento/{entrepreneurship_id}', [ClientController::class,'show'])->middleware(['role:client','permission:show-client']);
    Route::post('clientes', [ClientController::class,'store'])->middleware(['role:client','permission:store-client']);
    Route::put('clientes/{client}', [ClientController::class,'update'])->middleware(['role:client','permission:update-client']);
    Route::delete('clientes/{id}/emprendimiento/{entrepreneurship_id}', [ClientController::class,'destroy'])->middleware(['role:client','permission:delete-client']);
    Route::post('clientes/buscar_dni', [ClientController::class,'searchByDNI'])->middleware(['role:client','permission:searchByDni-client']);


    /**
     * Cashflow / Flujo de caja
     */
    Route::get('flujo_dinero/emprendimiento/{id}', [CashflowController::class,'index'])->middleware(['role:client','permission:index-cashflow']);
    Route::get('flujo_dinero/{id}/emprendimiento/{entrepreneurship_id}', [CashflowController::class,'show'])->middleware(['role:client','permission:show-cashflow']);
    Route::post('flujo_dinero', [CashflowController::class,'store'])->middleware(['role:client','permission:store-cashflow']);
    Route::put('flujo_dinero/{cashflow}', [CashflowController::class,'update'])->middleware(['role:client','permission:update-cashflow']);
    Route::delete('flujo_dinero/{id}/emprendimiento/{entrepreneurship_id}', [CashflowController::class,'destroy'])->middleware(['role:client','permission:delete-cashflow']);
    Route::get('flujo_dinero_diario/emprendimiento/{id}', [CashflowController::class,'daily'])->middleware(['role:client','permission:daily-cashflow']);
    Route::get('flujo_dinero_semanal/emprendimiento/{id}', [CashflowController::class,'weekly'])->middleware(['role:client','permission:weekly-cashflow']);
    Route::get('flujo_dinero_mensual/emprendimiento/{id}', [CashflowController::class,'monthly'])->middleware(['role:client','permission:monthly-cashflow']);
    Route::get('flujo_dinero_historico/emprendimiento/{id}', [CashflowController::class,'historic'])->middleware(['role:client','permission:historic-cashflow']);
    

    /**
     * Extra / Detalle extra
     */
    Route::get('detalle_extras/reserva/{booking_id}', [ExtraController::class,'index'])->middleware(['role:client','permission:index-extra']);
    Route::get('detalle_extras/{id}', [ExtraController::class,'show'])->middleware(['role:client','permission:show-extra']);
    Route::post('detalle_extras/reserva/{booking_id}', [ExtraController::class,'store'])->middleware(['role:client','permission:store-extra']);
    Route::put('detalle_extras/{extra}/reserva/{booking_id}', [ExtraController::class,'update'])->middleware(['role:client','permission:update-extra']);
    Route::delete('detalle_extras/{id}/reserva/{booking_id}', [ExtraController::class,'destroy'])->middleware(['role:client','permission:delete-extra']);


    /**
     * Observation / Observaciones
     */
    Route::get('observaciones/reserva/{booking_id}', [ObservationController::class, 'index'])->middleware(['role:client','permission:index-observation']);
    Route::post('observaciones', [ObservationController::class, 'store'])->middleware(['role:client','permission:store-observation']);
    Route::put('observaciones/{observation}', [ObservationController::class, 'update'])->middleware(['role:client','permission:update-observation']);	
    Route::delete('observaciones/{id}', [ObservationController::class, 'destroy'])->middleware(['role:client','permission:delete-observation']);


    /**
     * BookingAccommodation / Reservas con alojamiento
     */
    Route::post('reservas_alojamiento/lista', [BookingAccommodationController::class, 'index'])->middleware(['role:client','permission:index-bookingAccommodation']);
    Route::get('reservas_alojamiento/{id}', [BookingAccommodationController::class, 'show'])->middleware(['role:client','permission:show-bookingAccommodation']);
    Route::get('reservas_alojamiento/{id}/detalle', [BookingAccommodationController::class, 'detailBooking'])->middleware(['role:client','permission:detailBooking-bookingAccommodation']);
    Route::post('reservas_alojamiento/busqueda_por_cliente', [BookingAccommodationController::class, 'searchBookingByClient'])->middleware(['role:client','permission:searchBookingByClient-bookingAccommodation']);
    Route::post('reservas_alojamiento/disponibilidad', [BookingAccommodationController::class, 'availability'])->middleware(['role:client','permission:availability-bookingAccommodation']);
    Route::post('reservas_alojamiento', [BookingAccommodationController::class, 'storeBooking'])->middleware(['role:client','permission:storeBooking-bookingAccommodation']);
    Route::delete('reservas_alojamiento/{id}', [BookingAccommodationController::class, 'destroy'])->middleware(['role:client','permission:destroy-bookingAccommodation']);
    Route::put('reservas_alojamiento/{booking}', [BookingAccommodationController::class, 'updateBooking'])->middleware(['role:client','permission:updateBooking-bookingAccommodation']);
    Route::post('reservas_alojamiento/checkin', [BookingAccommodationController::class, 'checkin'])->middleware(['role:client','permission:checkin-bookingAccommodation']);
    Route::post('reservas_alojamiento/checkout', [BookingAccommodationController::class, 'checkout'])->middleware(['role:client','permission:checkout-bookingAccommodation']);
    Route::post('reservas_alojamiento/sin_checkout', [BookingAccommodationController::class, 'bookingsWithoutCheckout'])->middleware(['role:client','permission:bookingsWithoutCheckout-bookingAccommodation']);


    /**
     * DetailBooking / Detalle reserva - Servicio Opcional 
     */
    Route::post('reservas/agregar_servicio_opcional', [DetailBookingController::class, 'addOptionalServicesDetail'])->middleware(['role:client','permission:addOptionalServicesDetail-bookingDetail']);
    Route::post('reservas/remover_servicio_opcional', [DetailBookingController::class, 'removeOptionalServicesDetail'])->middleware(['role:client','permission:removeOptionalServicesDetail-bookingDetail']);
    Route::post('reservas/actualizar_servicio_opcional', [DetailBookingController::class, 'updateOptionalServicesQuantityDetail'])->middleware(['role:client','permission:updateOptionalServicesQuantityDetail-bookingDetail']);

    
    /**
     * BookingWithoutAccommodation / Reservas sin alojamiento
     */
    Route::post('reservas/lista', [BookingWithoutAccommodationController::class, 'getBookingsByDate'])->middleware(['role:client','permission:getBookingsByDate-bookingWithoutAccommodation']);         
    Route::get('reservas/{id}', [BookingWithoutAccommodationController::class, 'show'])->middleware(['role:client','permission:show-bookingWithoutAccommodation']);                        
    Route::get('reservas/{id}/detalle', [BookingWithoutAccommodationController::class, 'detailBooking'])->middleware(['role:client','permission:detailBooking-bookingWithoutAccommodation']);       
    Route::post('reservas', [BookingWithoutAccommodationController::class, 'store'])->middleware(['role:client','permission:store-bookingWithoutAccommodation']);                           
    Route::post('reservas/checkout', [BookingWithoutAccommodationController::class, 'checkout'])->middleware(['role:client','permission:checkout-bookingWithoutAccommodation']);               
    Route::put('reservas/{booking}/actualizar', [BookingWithoutAccommodationController::class, 'update'])->middleware(['role:client','permission:update-bookingWithoutAccommodation']);      
    Route::delete('reservas/{id}', [BookingWithoutAccommodationController::class, 'destroy'])->middleware(['role:client','permission:delete-bookingWithoutAccommodation']);                  
    Route::post('reservas/sin_checkout', [BookingWithoutAccommodationController::class, 'bookingsWithoutCheckout'])->middleware(['role:client','permission:bookingsWithoutCheckout-bookingWithoutAccommodation']);

});

