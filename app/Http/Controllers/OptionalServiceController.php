<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OptionalService;
use App\Models\Entrepreneurship;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\OptionalServiceRequest;
use App\Http\Resources\OptionalServiceResource;

class OptionalServiceController extends Controller
{
    
    public function index(Request $request)
    {
        $entrepreneurship = Entrepreneurship::with('optionalServices')->findOrFail($request->id);
        return OptionalServiceResource::collection($entrepreneurship->optionalServices);
    }

    
    public function store(OptionalServiceRequest $request)
    {
        try {
            DB::beginTransaction();

            $entrepreneurship = Entrepreneurship::with('optionalServices')->findOrFail($request->id);
            $optionalService = OptionalService::create($request->all());
            $entrepreneurship->optionalServices()->save($optionalService);
            $entrepreneurship->refresh();

            DB::commit();
            return (OptionalServiceResource::collection($entrepreneurship->optionalServices))->additional(['ok' => true, 'msg' => 'Servicio opcional creado con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        } 
    }

    
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $optionalService = OptionalService::find($request->id);
            $optionalService->fill($request->all());
            $optionalService->save();

            $entrepreneurship = Entrepreneurship::with('optionalServices')->findOrFail($request->entrepreneurship_id);

            DB::commit();
            return (OptionalServiceResource::collection($entrepreneurship->optionalServices))->additional(['ok' => true, 'msg' => 'Servicio opcional actualizado con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        } 
    }

    
    public function destroy(Request $request)
    {
        try {
            DB::beginTransaction();

            $optionalService = OptionalService::findOrFail($request->id);
            $optionalService->entrepreneurships()->detach();
            $optionalService->bookings()->detach();
            $optionalService->delete();

            $entrepreneurship = Entrepreneurship::with('optionalServices')->findOrFail($request->entrepreneurship_id);

            DB::commit();
            return (OptionalServiceResource::collection($entrepreneurship->optionalServices))->additional(['ok' => true, 'msg' => 'Servicio opcional eliminado con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        } 
    }

}
