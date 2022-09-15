<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use Illuminate\Support\Facades\Validator;


class ClientController extends Controller
{
    
    public function index(Request $request)
    {
        return ClientResource::collection(Client::where('entrepreneurship_id', $request->id)->get());
    }


    public function store(ClientRequest $request)
    {
        try {
            DB::beginTransaction();

            $client = Client::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'dni' => $request->dni,
                'date_birth' => date('Y-m-d', strtotime($request->date_birth)),
                'reason' => $request->reason,
                'departure_locality' => $request->departureLocality,
                'residence_locality' => $request->residenceLocality,
                'entrepreneurship_id' => $request->entrepreneurship_id,
            ]);

            DB::commit();
            return (new ClientResource($client))->additional(['msg' => 'cliente creado con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        } 
    }


    public function show(Request $request)
    {
        return new ClientResource(Client::where('id', $request->id)->where('entrepreneurship_id', $request->entrepreneurship_id)->first());
    }


    public function searchByDNI(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'dni' => 'requerid|numeric|max:8',
        ]);
        
        $client = Client::where('dni', $request->input('dni'))->first();
        if ($client) {
            return new ClientResource($client);
        } else {
            return response()->json(['msg' => 'cliente no encontrado.','ok' => false]);
        }
    }


    public function update(ClientRequest $request, Client $client)
    {
        try {
            DB::beginTransaction();

            $client->fill([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'dni' => $request->dni,
                'date_birth' => date('Y-m-d', strtotime($request->date_birth)),
                'reason' => $request->reason,
                'departure_locality' => $request->departureLocality,
                'residence_locality' => $request->residenceLocality,
                'entrepreneurship_id' => $request->entrepreneurship_id,
            ]);
            $client->save();

            DB::commit();
            return (new ClientResource($client))->additional(['msg' => 'cliente actualizado con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        } 
    }


    public function destroy(Request $request)
    {
        try {
            DB::beginTransaction();

            $client = Client::where('id', $request->id)->where('entrepreneurship_id', $request->entrepreneurship_id)->first();
            $client->delete();

            DB::commit();
            return (new ClientResource($client))->additional(['msg' => 'Cliente eliminado con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }


}
