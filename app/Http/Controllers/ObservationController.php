<?php

namespace App\Http\Controllers;

use App\Http\Requests\ObservationRequest;
use App\Models\Observation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\ObservationResource;

class ObservationController extends Controller
{
    
    public function index(Request $request)
    {
        return ObservationResource::collection(Observation::where('booking_id', $request->booking_id)->get());
    }

    public function store(ObservationRequest $request)
    {
        try {
            DB::beginTransaction();

            $observation = Observation::create($request->all());

            DB::commit();
            return (new ObservationResource($observation))->additional(['msg' => 'observación creado con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        } 
    }

    public function update(ObservationRequest $request, Observation $observation)
    {
        try {
            DB::beginTransaction();

            $observation = Observation::findOrFail($request->id);
            $observation->fill($request->all());
            $observation->save();

            DB::commit();
            return (new ObservationResource($observation))->additional(['msg' => 'observación actualizado con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        } 
    }

    public function destroy(Request $request)
    {
        try {
            DB::beginTransaction();

            $observation = Observation::findOrFail($request->id);
            $observation->delete();

            DB::commit();
            return (new ObservationResource($observation))->additional(['msg' => 'observación eliminado con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        } 
    }



}
