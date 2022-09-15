<?php

namespace App\Http\Controllers;

use App\Models\LocalService;
use Illuminate\Http\Request;
use App\Models\Entrepreneurship;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\LocalServiceRequest;
use App\Http\Resources\LocalServiceResource;
use App\Models\Serviceable;

class LocalServiceController extends Controller
{
    
    public function index(Request $request)
    {
        $entrepreneurship = Entrepreneurship::with('localServices')->findOrFail($request->id);
        $localServiceCategories = LocalService::select('category')->groupBy('category')->get();
        return (LocalServiceResource::collection($entrepreneurship->localServices))->additional(['categories' => $localServiceCategories, 'ok' => true]);
    }

    
    public function store(LocalServiceRequest $request)
    {
        try {
            DB::beginTransaction();

            $entrepreneurship = Entrepreneurship::with('localServices')->findOrFail($request->id);
            $localService = LocalService::create($request->all());
            $entrepreneurship->localServices()->save($localService);
            $entrepreneurship->refresh();

            DB::commit();
            return (LocalServiceResource::collection($entrepreneurship->localServices))->additional(['ok' => true, 'msg' => 'Servicio local creado con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        } 
    }

    
    public function update(LocalServiceRequest $request, LocalService $localService)
    {
        try {
            DB::beginTransaction();

            $localService = LocalService::find($request->id);
            $localService->fill($request->all());
            $localService->save();

            $entrepreneurship = Entrepreneurship::with('localServices')->findOrFail($request->entrepreneurship_id);

            DB::commit();
            return (LocalServiceResource::collection($entrepreneurship->localServices))->additional(['ok' => true, 'msg' => 'Servicio local actualizado con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        } 
    }


    public function destroy(Request $request)
    {
        
        $localService = LocalService::findOrFail($request->id);
        $localService->entrepreneurships()->detach();
        $localService->installations()->detach();
        $localService->delete();

        $entrepreneurship = Entrepreneurship::with('localServices')->findOrFail($request->entrepreneurship_id);
        
        return (LocalServiceResource::collection($entrepreneurship->localServices))->additional(['msg' => 'Servicio local eliminado con éxito.']);
    }



}
