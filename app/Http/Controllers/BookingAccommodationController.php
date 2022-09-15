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
use App\Http\Requests\BookingAccommodation\CheckinRequest;
use App\Http\Requests\BookingAccommodation\UpdateBookingRequest;
use App\Http\Requests\BookingAccommodation\BookingAvailabilityRequest;
use App\Http\Requests\BookingAccommodation\BookingAccommodationRequest;


class BookingAccommodationController extends Controller
{

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dateFrom' => 'required|date',
            'dateTo' => 'required|date',
            'entrepreneurship_id' => 'required|integer',
        ]);

        $eid = $request->input('entrepreneurship_id');
        $date_from = Carbon::parse($request->input('dateFrom'))->format('Y-m-d 00:00:00');
        $date_to = Carbon::parse($request->input('dateTo'))->format('Y-m-d 23:59:59');

        $bookings = Booking::whereBetween('date_from',[$date_from,$date_to])
                            ->orWhereBetween('date_to',[$date_from,$date_to])
                            ->orWhere([
                                ['date_from', '<', $date_from],
                                ['date_to', '>', $date_to],
                            ])
                            ->whereHas('client', function($query) use ($eid){
                                $query->where('entrepreneurship_id','=', $eid );    
                            })
                            ->with('client')
                            ->get();
        
        if ($bookings->count() > 0) {
            return BookingResource::collection($bookings);
        } else {
            return response()->json(['msg' => 'No se encontraron reservas', 'ok' => true]);
        }
    }


    public function show(Request $request)
    {
        return new BookingResource(Booking::with('client','guests','payments',
                                                 'installations','optionalServices','extras',
                                                 'employeeCheckin','employeeCheckout','observations')
                                          ->findOrFail($request->id));
    }


    public function detailBooking(Request $request)
    {
        return new BookingResource(Booking::select('id','phone_contact','date_from','date_to')
                                            ->with('installations','optionalServices','extras')
                                            ->findOrFail($request->id)); 
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

    public function availability(BookingAvailabilityRequest $request)
    {

        $dateFrom = Carbon::parse($request->input('dateFrom'))->format('Y-m-d 00:00:00');
        $dateTo = Carbon::parse($request->input('dateTo'))->format('Y-m-d 23:59:59');
        $installation = Installation::with('bookings')->findOrFail($request->input('installation_id'));

        if ($request->input('quantityInstallations') > $installation->quantity) {
            return response()->json([
                'msg' => 'La cantidad demandada es mayor a las instalaciones de este tipo actualmente disponibles.', 
                'ok' => false
            ]);
        }

        $quantityGuests = $request->input('adults') + $request->input('minors');
        $totalRequestCapacity = $installation->capacity * $request->input('quantityInstallations');
        if ($quantityGuests > $totalRequestCapacity) {
            return response()->json([
                'msg' => 'La cantidad de personas supera la capacidad de la o las instalaciones elegidas', 
                'ok' => false
            ]);
        }

        $reservedCount = 0;
        foreach ($installation->bookings as $booking) {
            $bookingDateFrom = Carbon::parse($booking->date_from)->format('Y-m-d 00:00:00');
            $bookingDateTo = Carbon::parse($booking->date_to)->format('Y-m-d 23:59:59');
            if ($bookingDateFrom <= $dateFrom && $bookingDateTo >= $dateTo) {
                $reservedCount += 1;
            }
        }

        $diffReservedInstallationsQuantity = abs($reservedCount - $installation->quantity);
        
        $availability = false;
        if ($diffReservedInstallationsQuantity > 0 && $diffReservedInstallationsQuantity >= $request->input('quantityInstallations')) {
            $availability = true;
            return response()->json([
                'Disponibilidad' => $availability,
                'ok' => true
            ]);
        } elseif ($diffReservedInstallationsQuantity <= 0 || $diffReservedInstallationsQuantity < $request->input('quantityInstallations')) {
            return response()->json([
                'Disponibilidad' => $availability,
                'ok' => true
            ]);
        }
    }

    
    public function searchBookingByClient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dni' => 'requerid|numeric',
        ]);

        $client = Client::where('dni', $request->input('dni'))->first();
        if ($client) {
            $booking = Booking::with('client','guests','payments','installations','optionalServices',
                                    'extras','employeeCheckin','employeeCheckout','observations')
                                ->where('client_id',$client->id)
                                ->get()
                                ->last();

            return new BookingResource($booking);
        } else {
            return response()->json([
                'msg' => 'Usuario no encontrado.',
                'ok' => true,
            ]);
        }
    }


    public function storeBooking(BookingAccommodationRequest $request)
    {
        try {
            DB::beginTransaction();

            $installation = Installation::findOrFail($request->input('installation_id'));
            $client = Client::findOrFail($request->input('client_id'));
            $quantityInstallations = (int)$request->input('quantityInstallations');
            $requestOptionalServices = collect($request->input('optionalServices'))->map(function ($item) {
                return (object) $item;
            });
            
            $booking = Booking::create([
                'phone_contact' => $request->input('phoneContact'),
                'adults' => (int)$request->input('adults'),
                'minors' => (int)$request->input('minors'),
                'date_from' => Carbon::parse($request->input('dateFrom'))->format('Y-m-d 00:00:00'),
                'date_to' => Carbon::parse($request->input('dateTo'))->format('Y-m-d 23:59:59'),
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

            $booking->amount = $this->calculeTotalAmount($booking, $installation, $quantityInstallations);
            $booking->save();

            $booking->refresh();
            DB::commit();
            return (new BookingResource($booking))->additional(['msg' => 'Reserva creado con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }


    public function updateBooking(UpdateBookingRequest $request)
    {
        try {
            DB::beginTransaction();

            $quantityInstallationsRequest = (int)$request->input('quantityInstallations');
            $dateFromRequest = Carbon::parse($request->input('dateFrom'))->format('Y-m-d');
            $dateToRequest = Carbon::parse($request->input('dateTo'))->format('Y-m-d');
            $quantityPeople = (int)$request->input('adults') + (int)$request->input('minors');

            $booking = Booking::with('installations','guests','client')->findOrFail($request->booking);
            if ( $booking->checkin_date && ($quantityPeople !== count($request->input('guests'))) ) {
                return response()->json([
                    'error' => 'La cantidad de adultos y menores no es igual a la cantidad de huespedes.', 
                    'ok' => true,
                ]);
            }
            
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
            
            
            $availability = $this->checkAvailabilityUpdate($dateFromRequest, $dateToRequest, $installation, $quantityInstallationsRequest);            
            if($availability) {
                $booking->fill([
                    'phone_contact' => $request->input('phoneContact'),
                    'adults' => (int)$request->input('adults'),
                    'minors' => (int)$request->input('minors'),
                    'date_from' => Carbon::parse($request->input('dateFrom'))->format('Y-m-d H:i:s'),
                    'date_to' => Carbon::parse($request->input('dateTo'))->format('Y-m-d H:i:s'),
                    'client_id' => (int)$request->input('client_id'),
                ]);

                if ($updateDataPivot) {
                    $booking->installations()->updateExistingPivot($installation->id, ['price_unit' => $installation->price, 'quantity' => $quantityInstallationsRequest ]);
                    $booking->load('installations');
                    $booking->amount = $this->calculeTotalAmount($booking, $installation, $quantityInstallationsRequest);
                }
                
                $booking->save();
            } else {
                return response()->json([
                    'error' => 'No hay disponibilidad de reserva para la fecha solicitada, pruebe con otro día y/o una menor cantidad de instalaciones.', 
                    'ok' => true,
                ]);
            } 

            if (count($request->input('guests')) > 0 ) {
                $booking->guests()->sync($request->input('guests'));
            }

            $booking->unsetRelation('installations');

            DB::commit();
            $booking->refresh();
            return (new BookingResource($booking))->additional(['msg' => 'Reserva actualizada con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }

    
    public function checkin(CheckinRequest $request)
    { 
        try {
            DB::beginTransaction();

            $booking = Booking::with('client')->findOrFail($request->input('booking_id'));
            if ($booking->checkin_date) {
                return response()->json([
                    'error' => 'Ya se registro el checkin de esta reserva', 
                    'ok' => true,
                ]);
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

            $booking->checkin_date = Carbon::parse($request->input('checkinDate'))->format('Y-m-d H:i:s');
            $booking->checkin_employee_id = auth()->user()->employee->id;
            $booking->save();
            $booking->guests()->saveMany($guestsRequest);
            $booking->load('guests');
            
            DB::commit();
            return (new BookingResource($booking))->additional(['msg' => 'Checkin generado con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }

    
    public function checkout(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'booking_id' => 'required|integer',
                'checkoutDate' => 'required',
            ]);
            
            $booking = Booking::with('optionalServices','extras','installations')->findOrFail($request->input('booking_id'));
            if ($booking && $booking->checkin_date !== null) {
                $installation = $booking->installations->first();
                $quantityInstallations = $installation->pivot->quantity;
    
                $booking->checkout_date = Carbon::parse($request->input('checkoutDate'))->format('Y-m-d H:i:s');
                $booking->checkout_employee_id = auth()->user()->employee->id;
                $booking->save();
    
                $booking->amount = $this->calculeTotalAmount($booking, $installation, $quantityInstallations);
                $booking->save();
    
                $booking->unsetRelation('optionalServices')
                        ->unsetRelation('extras')
                        ->unsetRelation('installations');
                        
                DB::commit();
                $booking->refresh();
                return (new BookingResource($booking))->additional(['msg' => 'Checkout generado con éxito.']);
            } else {
                return response()->json([
                    'msg' => 'No se realizo el checkin para esta reserva.',
                    'ok' => true,
                ]);
            }
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

            $booking = Booking::with('guests')->findOrFail($request->id);
            
            if ($booking->guests()->count() == 0 && (!$booking->checkin_date || !$booking->checkout_date)) {
                $booking->guests()->detach();
                $booking->optionalServices()->detach();
                $booking->extras()->detach();
                $booking->installations()->detach();
                $booking->forceDelete();
            } else {
                $booking->unsetRelation('guests');
                $booking->delete();
            }
            
            DB::commit();
            $booking->refresh();
            return (new BookingResource($booking))->additional(['msg' => 'Reserva eliminada con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }



    public function calculeTotalAmount($booking, $installation, $quantityInstallations) {
        $booking->refresh();
        
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


    public function checkAvailabilityUpdate($dateFromRequest, $dateToRequest, $installation, $installationsQuantityRequest)
    {
        $instId = $installation->id;
        $bookings = Booking::with(['installations' => function($query) use ($instId) {
                                        $query->where('installations.id','=', $instId);  
                                      }])
                                ->whereDate('date_from', '<=', $dateFromRequest)
                                ->whereDate('date_to', '>=', $dateToRequest)
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
