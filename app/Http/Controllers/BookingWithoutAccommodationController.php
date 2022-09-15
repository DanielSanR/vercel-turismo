<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Booking;
use App\Models\Installation;
use Illuminate\Http\Request;
use App\Models\OptionalService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\BookingResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\BookingWithoutAccommodation\StoreBookingRequest;
use App\Http\Requests\BookingWithoutAccommodation\UpdateBookingRequest;

class BookingWithoutAccommodationController extends Controller
{
    
    public function getBookingsByDate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'installation_id' => 'requerid|integer',
            'entrepreneurship_id' => 'requerid|integer',
            'date' => 'required|date',
        ]);

        $installation = Installation::findOrFail($request->input('installation_id'));
        $instId = $installation->id;  
        $date = Carbon::parse($request->input('date'))->format('Y-m-d');
        $eid = (int)$request->input('entrepreneurship_id');

        $bookings = Booking::with('client', function($query) use ($eid) {
                                    $query->where('entrepreneurship_id','=', $eid );    
                                  },
                                  'installations', function($query) use ($instId) {
                                    $query->where('id','=', $instId);  
                                  },
                                  'guests'
                                )
                            ->whereDate('date_from', '=', $date)
                            ->whereDate('date_to', '=', $date)
                            ->get();
        
       
        $availability = $installation->quantity - $bookings->count();     
        if($availability <= 0) {
            return (BookingResource::collection($bookings))->additional(['availabilityQuantity' => 0, 'availability' => false]);
        }
        
        return (BookingResource::collection($bookings))->additional(['availabilityQuantity' => $availability, 'availability' => true]);
    }

    
    public function show(Request $request)
    {
        return new BookingResource(Booking::with('client','guests','payments',
                                                 'installations','optionalServices','employeeCheckin')
                                          ->findOrFail($request->id));
    }


    public function detailBooking(Request $request)
    {
        return new BookingResource(Booking::select('id','phone_contact','date_from','date_to')
                                            ->with('installations','optionalServices')
                                            ->findOrFail($request->id)); 
    }


    public function store(StoreBookingRequest $request)
    {
        try {
            DB::beginTransaction();

            $installation = Installation::findOrFail($request->input('installation_id'));
            $quantityInstallations = (int)$request->input('quantityInstallations');
            $requestOptionalServices = collect($request->input('optionalServices'))->map(function ($item) {
                return (object) $item;
            });
            
            $clientRequest = (object)$request->input('client');
            $client = Client::where('dni',$clientRequest->dni)->first();

            if(!$client) {
                $client = Client::create([
                    'first_name' => $clientRequest->firstName,
                    'last_name' => $clientRequest->lastName,
                    'dni' => $clientRequest->dni,
                    'date_birth' => Carbon::parse($clientRequest->dateBirth)->format('Y-m-d'),
                    'reason' => $clientRequest->reason,
                    'departure_locality' => $clientRequest->departureLocality,
                    'residence_locality' => $clientRequest->residenceLocality,
                    'entrepreneurship_id' => $clientRequest->entrepreneurship_id,
                ]);
            }
            
            $booking = Booking::create([
                'phone_contact' => $request->input('phoneContact'),
                'adults' => (int)$request->input('adults'),
                'minors' => (int)$request->input('minors'),
                'date_from' => Carbon::parse($request->input('dateFrom'))->format('Y-m-d H:i:00'),
                'date_to' => Carbon::parse($request->input('dateTo'))->format('Y-m-d H:i:59'),
                'checkin_date' => Carbon::parse($request->input('dateFrom'))->format('Y-m-d H:i:00'),
                'checkin_employee_id' => auth()->user()->employee->id,
                'checkout_employee_id' => auth()->user()->employee->id,
                'client_id' => $client->id,
            ]);
            $booking->load('client');

            $booking->installations()->attach($installation->id, ['price_unit' => $installation->price, 'quantity' => $quantityInstallations ]);
            $booking->load('installations');
            
            if ($requestOptionalServices->count() > 0) {
                foreach ($requestOptionalServices as $requestOptionalService) {
                    $optionalService = OptionalService::find($requestOptionalService->id);
                    $booking->optionalServices()->attach($optionalService->id, ['price_unit' => $optionalService->price, 'quantity' => $requestOptionalService->quantity]);
                }
                $booking->load('optionalServices');
            }
    
            $requestGuests = collect($request->input('guests'))->map(function ($item) {
                return (object) $item;
            });

            $guestsRequest = collect([$booking->client]);
            foreach ($requestGuests as $requestGuest) {
                $clientQuery = Client::where('dni', $requestGuest->dni)->first();
                if ($clientQuery) {
                    $guestQuery = $booking->guests->where('id','=',$clientQuery->id)->first();
                    if (!$guestQuery) {
                        $guestsRequest->add($clientQuery);
                    }
                } else {
                    $client = Client::create([
                        'first_name' => $requestGuest->firstName,
                        'last_name' => $requestGuest->lastName,
                        'dni' => $requestGuest->dni,
                        'date_birth' => Carbon::parse($requestGuest->dateBirth)->format('Y-m-d'),
                        'reason' => $requestGuest->reason,
                        'departure_locality' => $requestGuest->departureLocality,
                        'residence_locality' => $requestGuest->residenceLocality,
                        'entrepreneurship_id' => $booking->client->entrepreneurship_id,
                    ]);
                    $guestsRequest->add($client);
                }
            }

            $booking->guests()->saveMany($guestsRequest);
            $booking->load('guests');
            $booking->amount = $this->calculeTotalAmount($booking, $installation, $quantityInstallations);
            $booking->save();

            $booking->refresh();
            DB::commit();
            return (new BookingResource($booking))->additional(['msg' => 'Reserva creado con éxito.', 'ok' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }


    public function bookingsWithoutCheckout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'entrepreneurship_id' => 'requerid|integer',
        ]);

        $eid = $request->input('entrepreneurship_id');
        $bookings = Booking::with('client', function($query) use ($eid){
                                $query->where('entrepreneurship_id','=', $eid);    
                            })
                            ->whereNull('checkout_date')
                            ->get();
        
        if ($bookings->count() > 0) {
            return BookingResource::collection($bookings);
        } else {
            return response()->json([
                'msg' => 'No se encontraron reservas sin checkout confirmado',
                'ok' => true,
            ]);
        }
    }


    public function checkout(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'id' => 'requerid|integer',
                'checkoutDate' => 'requerid|date',
            ]);
            
            $booking = Booking::with('installations','client')->findOrFail($request->input('booking_id'));
            $installation = $booking->installations()->first();
            $quantityInstallations = $installation->pivot->quantity; 

            $booking->amount = $this->calculeTotalAmount($booking, $installation, $quantityInstallations);
            $booking->checkout_date = Carbon::parse($request->input('checkoutDate'))->format('Y-m-d H:i:s');
            $booking->save();
            
            $booking->unsetRelation('installations')
                    ->unsetRelation('optionalServices');

            DB::commit();
            return (new BookingResource($booking))->additional(['msg' => 'Reserva cerrada con éxito.', 'ok' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }


    public function update(UpdateBookingRequest $request)
    {
        try {
            DB::beginTransaction();

            $quantityPeople = (int)$request->input('adults') + (int)$request->input('minors');
            $quantityInstallationsRequest = (int)$request->input('quantityInstallations');
            $dateFromRequest = Carbon::parse($request->input('dateFrom'))->format('Y-m-d H:i:s');
            $dateToRequest = Carbon::parse($request->input('dateTo'))->format('Y-m-d H:i:s');

            if ($quantityPeople !== count($request->input('guests'))) {
                return response()->json([
                    'error' => 'La cantidad de adultos y menores no es igual a la cantidad de clientes.', 
                    'ok' => true,
                ]);
            }

            $booking = Booking::with('installations','guests','client')->findOrFail($request->booking);
            $installation = $booking->installations()->first();
            $quantityInstallationsCurrent = $installation->pivot->quantity;

            $updateDataPivot = false;
            if ($quantityInstallationsRequest === $quantityInstallationsCurrent) {
                $totalCapacityCurrent = $installation->pivot->quantity * $installation->capacity;
                if ($quantityPeople > $totalCapacityCurrent) {
                    return response()->json([
                        'error' => 'La cantidad de personas supera la capacidad de la o las intalaciones reservadas.', 
                        'ok' => true,
                    ]);
                }
            } else {
                $newTotalCapacity = $quantityInstallationsRequest * $installation->capacity;
                if ($quantityPeople > $newTotalCapacity) {
                    return response()->json([
                        'error' => 'La cantidad de personas supera la capacidad de la o las intalaciones reservadas.', 
                        'ok' => true,
                    ]);
                }
                $updateDataPivot = true;
            }

            $dateFromDB = Carbon::parse($booking->date_from)->format('Y-m-d H:i:s');
            $dateToDB = Carbon::parse($booking->date_to)->format('Y-m-d H:i:s');

            if (($dateFromDB === $dateFromRequest) && ($dateToDB === $dateToRequest)) {
                $availability = true;
            } else {
                $availability = $this->checkAvailabilityUpdate($dateFromRequest, $dateToRequest, $installation, $quantityInstallationsRequest);
            }
            
            if($availability) {
                $booking->fill([
                    'phone_contact' => $request->input('phoneContact'),
                    'adults' => (int)$request->input('adults'),
                    'minors' => (int)$request->input('minors'),
                    'date_from' => $dateFromRequest,
                    'date_to' => $dateToRequest,
                    'client_id' => (int)$request->input('client_id'),
                ]);
                
                if ($updateDataPivot) {
                    $booking->installations()->updateExistingPivot($installation->id, ['price_unit' => $installation->price, 'quantity' => $quantityInstallationsRequest ]);
                    $booking->load('installations');
                }
                
                $booking->save();
            } else {
                return response()->json([
                    'error' => 'No hay disponibilidad de reserva para la fecha y hora solicitada, pruebe con otro día y horario, o una menor cantidad de instalaciones.', 
                    'ok' => true,
                ]);
            } 

            if (count($request->input('guests')) > 0 ) {
                $booking->guests()->sync($request->input('guests'));
            }
            
            $booking->unsetRelation('installations');

            DB::commit();
            $booking->refresh();
            return (new BookingResource($booking))->additional(['msg' => 'Reserva actualizada con éxito.','ok' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }

    }


    public function destroy(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
            ]);

            $booking = Booking::findOrFail($request->id);

            if ($booking->checkout_date) {
                $booking->delete();
            } else {
                $booking->guests()->detach();
                $booking->optionalServices()->detach();
                $booking->installations()->detach();
                $booking->forceDelete();
            }

            DB::commit();
            return (new BookingResource($booking))->additional(['msg' => 'Reserva eliminada con éxito.','ok' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }


    public function calculeTotalAmount($booking, $installation, $quantityInstallations) {
        $booking->refresh();
        $diffDays = Carbon::parse($booking->date_from)->diffInDays(Carbon::parse($booking->date_to));
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


    public function checkAvailabilityUpdate($dateFromRequest, $dateToRequest, $installation, $installationsQuantityRequest)
    {
        $instId = $installation->id;
        $bookings = Booking::with(['installations' => function($query) use ($instId) {
                                        $query->where('installations.id','=', $instId);  
                                      }])
                                ->whereDate('date_from', '=', $dateFromRequest)
                                ->whereDate('date_to', '=', $dateToRequest)
                                ->get();
        
        $countInstallationsPerBookings = 0;               
        foreach ($bookings as $booking) {
            $installation = $booking->installations()->first();
            $countInstallationsPerBookings += $installation->pivot->quantity;
        }

        if (($countInstallationsPerBookings + $installationsQuantityRequest) <= $installation->quantity) {
            return true;
        } 

        return false;
    }



}
