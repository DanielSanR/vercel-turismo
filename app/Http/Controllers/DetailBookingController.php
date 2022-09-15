<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\OptionalService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\BookingResource;
use App\Http\Requests\DetailBooking\addOptionalServicesDetailRequest;
use App\Http\Requests\DetailBooking\removeOptionalServicesDetailRequest;
use App\Http\Requests\DetailBooking\updateOptionalServicesDetailRequest;

class DetailBookingController extends Controller
{
    
    public function addOptionalServicesDetail(addOptionalServicesDetailRequest $request)
    {
        try {
            DB::beginTransaction();

            $booking = Booking::with('optionalServices','installations')->findOrFail($request->input('booking_id'));
            $installation = $booking->installations->first();
            $quantityInstallations = $installation->pivot->quantity;

            if (count($request->input('optionalServices')) > 0 ) {
                $requestOptionalServices = collect($request->input('optionalServices'))->map(function ($item) {
                    return (object) $item;
                });

                $flag = false;
                foreach ($requestOptionalServices as $requestOptionalService) {
                    foreach ($booking->optionalServices as $optionalService) {
                        if ($optionalService->id === $requestOptionalService->id) {
                            $optionalService->pivot->quantity += $requestOptionalService->quantity;
                            $optionalService->pivot->save();
                            $flag = true;
                        }
                    }
                    if (!$flag) {
                        $verificationOptionalService = OptionalService::find($requestOptionalService->id); 
                        if (!$verificationOptionalService) {
                            return response()->json([
                                'msg' => 'Servicio no encontrado.',
                                'ok' => true
                            ]);
                        }
                        $booking->optionalServices()
                                ->attach($verificationOptionalService->id, [
                                    'price_unit' => $verificationOptionalService->price, 
                                    'quantity' => $requestOptionalService->quantity
                                ]);
                        
                        
                        $booking->amount = $this->calculeTotalAmount($booking, $installation, $quantityInstallations);
                        $booking->save();
                        $booking->unsetRelation('installations');    
                        $flag = false;
                    }
                }
            } else {
                return response()->json([
                    'msg' => 'No se enviaron servicios opcinales para agregar a la reserva.',
                    'ok' => true
                ]);
            }

            DB::commit();
            $booking->refresh();
            return (new BookingResource($booking))->additional(['msg' => 'Detalle agregado con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }


    public function updateOptionalServicesQuantityDetail(updateOptionalServicesDetailRequest $request)
    {
        
        try {
            DB::beginTransaction();

            $booking = Booking::with('optionalServices')->findOrFail($request->input('booking_id'));
            $optionalService = OptionalService::findOrFail($request->input('optionalservice_id'));
            
            $booking->optionalServices()
                    ->updateExistingPivot($optionalService->id, [
                        'quantity' => $request->input('quantity'),
                    ]);

            DB::commit();
            $booking->refresh();
            return (new BookingResource($booking))->additional(['msg' => 'Detalle actualizado con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }


    public function removeOptionalServicesDetail(removeOptionalServicesDetailRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $booking = Booking::with('optionalServices','installations')->findOrFail($request->input('booking_id'));
            $booking->optionalServices()->detach($request->input('optionalservice_id'));
            
            $installation = $booking->installations->first();
            $quantityInstallations = $installation->pivot->quantity;
            $booking->amount = $this->calculeTotalAmount($booking, $installation, $quantityInstallations);
            $booking->save();
            $booking->unsetRelation('installations');    

            DB::commit();
            $booking->refresh();
            return (new BookingResource($booking))->additional(['msg' => 'Detalle removido con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }


    //----------------------------------------------------------------------------------------------------------------------------------------//
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



}
