<?php

namespace App\Http\Controllers;

use App\Models\Extra;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ExtraRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\ExtraResource;

class ExtraController extends Controller
{
    
    public function index(Request $request)
    {
        $booking = Booking::with('extras')->findOrFail($request->booking_id);
        return (ExtraResource::collection($booking->extras))->additional(['ok' => true]);
    }


    public function show(Request $request)
    {
        return new ExtraResource(Extra::findOrFail($request->id));
    }


    public function store(ExtraRequest $request)
    {
        try {
            DB::beginTransaction();

            $booking = Booking::findOrFail($request->booking_id);

            $extra = Extra::create($request->all());
            $booking->extras()->attach($extra->id, ['price_unit' => $extra->price]);
            
            
            DB::commit();
            return (new ExtraResource($extra))->additional(['msg' => 'Detalle extra creado con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        } 
    }


    public function update(ExtraRequest $request, Extra $extra)
    {
        try {
            DB::beginTransaction();

            $booking = Booking::findOrFail($request->booking_id);
            $extra->fill($request->all())->save();
            
            DB::commit();
            return (new ExtraResource($extra))->additional(['msg' => 'Detalle extra actualizado con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        } 
    }


    public function destroy(Request $request)
    {
        try {
            DB::beginTransaction();

            $booking = Booking::findOrFail($request->booking_id);
            $extra = $booking->extras->where('id', $request->id)->first();

            $booking->extras()->detach($request->id);
            $extra->delete();
            
            DB::commit();
            return (new ExtraResource($extra))->additional(['msg' => 'Detalle extra eliminada con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        } 
    }


}
