<?php

namespace App\Http\Controllers;

use App\Models\Cashflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\CashflowRequest;
use App\Http\Resources\CashflowResource;
use Carbon\Carbon;

class CashflowController extends Controller
{
    

    public function index(Request $request)
    {
        $cashflows = Cashflow::where('entrepreneurship_id', $request->id)->get();
        $totalIncomeEgress = $this->totalIncomeEgress($cashflows);
        $cashflowPaginate = Cashflow::where('entrepreneurship_id', $request->id)
                                    ->orderBy('id', 'desc')
                                    ->paginate(5);

        return (CashflowResource::collection($cashflowPaginate))->additional(['sumatoria' => $totalIncomeEgress]);
    }


    public function store(CashflowRequest $request)
    {
        try {
            DB::beginTransaction();

            $cashflow = Cashflow::create($request->all());

            DB::commit();
            return (new CashflowResource($cashflow))->additional(['msg' => 'Movimiento registrado con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        } 
    }


    public function show(Request $request)
    {
        return new CashflowResource(Cashflow::where('id', $request->id)
                                            ->where('entrepreneurship_id', $request->entrepreneurship_id)
                                            ->first());
    }


    public function update(CashflowRequest $request, Cashflow $cashflow)
    {
        try {
            DB::beginTransaction();

            $cashflow = Cashflow::findOrFail($request->id);
            $cashflow->fill($request->all());
            $cashflow->save();

            DB::commit();
            return (new CashflowResource($cashflow))->additional(['msg' => 'Movimiento actualizado con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        } 
    }


    public function destroy(Request $request)
    {
        try {
            DB::beginTransaction();

            $cashflow = Cashflow::where('id', $request->id)
                                ->where('entrepreneurship_id', $request->entrepreneurship_id)
                                ->first();

            $cashflow->delete();

            DB::commit();
            return (new CashflowResource($cashflow))->additional(['msg' => 'Movimiento eliminado con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        } 
    }


    public function daily(Request $request)
    {
        $cashflows = Cashflow::where('entrepreneurship_id', $request->id)
                             ->whereDate('created_at', Carbon::now()->format('Y-m-d'))
                             ->get();

        $totalIncomeEgress = $this->totalIncomeEgress($cashflows);

        $cashflowPaginate = Cashflow::where('entrepreneurship_id', $request->id)
                                    ->whereDate('created_at', Carbon::now()->format('Y-m-d'))
                                    ->orderBy('id', 'desc')
                                    ->paginate(5);

        return (CashflowResource::collection($cashflowPaginate))->additional(['sumatoria' => $totalIncomeEgress]);
    }


    public function weekly(Request $request)
    {
        $cashflows = Cashflow::where('entrepreneurship_id', $request->id)
                             ->whereBetween('created_at', [
                                Carbon::now()->startOfWeek()->format('Y-m-d'),
                                Carbon::now()->endOfWeek()->format('Y-m-d')
                             ])->get();

        $totalIncomeEgress = $this->totalIncomeEgress($cashflows);

        $cashflowPaginate = Cashflow::where('entrepreneurship_id', $request->id)
                                     ->whereBetween('created_at', [
                                        Carbon::now()->startOfWeek()->format('Y-m-d'),
                                        Carbon::now()->endOfWeek()->format('Y-m-d')
                                     ])
                                     ->orderBy('id', 'desc')
                                     ->paginate(5);

        return (CashflowResource::collection($cashflowPaginate))->additional(['sumatoria' => $totalIncomeEgress]);
    }


    public function monthly(Request $request)
    {
        $cashflows = Cashflow::where('entrepreneurship_id', $request->id)
                             ->whereBetween('created_at', [
                                Carbon::now()->startOfMonth()->format('Y-m-d'), 
                                Carbon::now()->endOfMonth()->format('Y-m-d')
                             ])->get();

        $totalIncomeEgress = $this->totalIncomeEgress($cashflows);

        $cashflowPaginate = Cashflow::where('entrepreneurship_id', $request->id)
                                    ->whereBetween('created_at', [
                                        Carbon::now()->startOfMonth()->format('Y-m-d'), 
                                        Carbon::now()->endOfMonth()->format('Y-m-d')
                                    ])
                                    ->orderBy('id', 'desc')
                                    ->paginate(5);

        return (CashflowResource::collection($cashflowPaginate))->additional(['sumatoria' => $totalIncomeEgress]);
    }

    public function historic(Request $request)
    {
        $cashflows = Cashflow::where('entrepreneurship_id', $request->id)->get();
        $totalIncomeEgress = $this->totalIncomeEgress($cashflows);
        $cashflowPaginate = Cashflow::where('entrepreneurship_id', $request->id)
                                    ->orderBy('id', 'desc')
                                    ->paginate(5);

        return (CashflowResource::collection($cashflowPaginate))->additional(['sumatoria' => $totalIncomeEgress]);
    }


    protected function totalIncomeEgress($cashflows)
    {
        $totalIncomeEgress = (object) [
            'Ingresos' => $cashflows->where('type','Ingreso')->sum('amount'),
            'Egresos' => $cashflows->where('type','Egreso')->sum('amount'),
        ];

        return $totalIncomeEgress;
    }





}
