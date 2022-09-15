<?php

namespace App\Http\Controllers;

use App\Models\Workday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\WorkdayRequest;
use App\Http\Resources\WorkdayResource;

class WorkdayController extends Controller
{
    

    public function index(Request $request)
    {
        return WorkdayResource::collection(Workday::where('entrepreneurship_id', $request->id)->get());
    }
    

    public function store(WorkdayRequest $request)
    {
        try {
            DB::beginTransaction();

            $workdays = Workday::where('entrepreneurship_id', $request->entrepreneurship_id)->get();

            foreach ($workdays as $wd) {
                if ($wd->day === $request->day) {
                    return response()->json([
                        'ok' => false,
                        'msg' => 'Ya existe este día asignado, desea actualizarlo?'
                    ]);
                }
            }

            $workday = Workday::create([
                'day' => $request->input('day'),
                'opening' => $request->input('opening'),
                'closing' => $request->input('closing'),
                'time_interval' => $request->input('timeInterval'),
                'entrepreneurship_id' => $request->input('entrepreneurship_id'),
            ]);

            DB::commit();
            return (new WorkdayResource($workday))->additional([
                'ok' => true,
                'msg' => 'Día laboral creado con éxito.'
            ]);
        } catch (\Throwable $exception) {
            DB::rollBack();
            return response()->json([
                'ok' => false,
                'error' => 'Error, no se pudo crear el día laboral.',
            ]);
        }
    }

    
    public function show(Request $request)
    {
        //
    }


    public function update(WorkdayRequest $request, Workday $workday)
    {
        try {
            DB::beginTransaction();

            $workdays = Workday::where('entrepreneurship_id', $request->entrepreneurship_id)->get();
            foreach ($workdays as $wd) {
                if ($wd->day === $request->day && $wd->id != $request->id) {
                    return response()->json([
                        'ok' => false,
                        'msg' => 'Ya existe una fecha con ese dia asignado.'
                    ]);
                }
            }

            $workday = Workday::findOrFail($request->id);
            $workday->fill([
                'day' => $request->input('day'),
                'opening' => $request->input('opening'),
                'closing' => $request->input('closing'),
                'time_interval' => (int)$request->input('timeInterval'),
                'entrepreneurship_id' => (int)$request->input('entrepreneurship_id'),
            ]);
            $workday->save();

            DB::commit();
            return (new WorkdayResource($workday))->additional([
                'ok' => true,
                'msg' => 'Día laboral actualizado con éxito.'
            ]);
        } catch (\Throwable $exception) {
            DB::rollBack();
            return response()->json([
                'ok' => false,
                'error' => 'Error, no se pudo actualizar el día laboral.',
            ]);
        }
    }

    
    public function destroy(Request $request)
    {

        $workday = Workday::findOrFail($request->id);
        $workday->delete();

        return (new WorkdayResource($workday))->additional([
            'ok' => true,
            'msg' => 'Día laboral eliminado con éxito.'
        ]);
    }
}
