<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entrepreneurship;
use App\Http\Requests\EntrepreneurshipRequest;
use App\Http\Resources\EntrepreneurshipResource;
use Illuminate\Support\Facades\DB;


class EntrepreneurshipController extends Controller
{
    

    public function index()
    {
        return EntrepreneurshipResource::collection(Entrepreneurship::all());
    }

    
    public function store(EntrepreneurshipRequest $request)
    {
        try {
            DB::beginTransaction();

            $entrepreneurship = Entrepreneurship::create($request->all());

            DB::commit();
            return (new EntrepreneurshipResource($entrepreneurship))->additional([
                'msg' => 'Emprendimiento creado con éxito.'
            ]);
        } catch (\Throwable $exception) {
            DB::rollBack();
            return response()->json([
                'ok' => false,
                'error' => 'Error, no se pudo crear el emprendimiento.',
            ]);
        }
    }

    
    public function show(Request $request)
    {
        $entrepreneurship = Entrepreneurship::findOrFail($request->id)->with('users','workdays','employees','localServices','optionalServices')->get();
        //return EntrepreneurshipResource::collection($entrepreneurship)->additional(['ok' => true]);
        return new EntrepreneurshipResource(Entrepreneurship::with('users','workdays','employees','localServices','optionalServices')->findOrFail($request->id));
    }


    public function update(EntrepreneurshipRequest $request, Entrepreneurship $entrepreneurship)
    {
        try {
            DB::beginTransaction();

            $entrepreneurship = Entrepreneurship::findOrfail($request->id);
            $entrepreneurship->fill($request->all());
            $entrepreneurship->save();

            DB::commit();
            return (new EntrepreneurshipResource($entrepreneurship))->additional(['msg' => 'Emprendimiento actualizado con éxito.']);
        } catch (\Throwable $exception) {
            DB::rollBack();
            return response()->json([
                'ok' => false,
                'error' => 'Error, no se pudo actulizar el emprendimiento.',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Entrepreneurship  $entrepreneurship
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        
        $entrepreneurship = Entrepreneurship::findOrFail($request->id);
        $entrepreneurship->delete();

        return (new EntrepreneurshipResource($entrepreneurship))->additional(['msg' => 'Emprendimiento eliminado con éxito']);
    }
}
