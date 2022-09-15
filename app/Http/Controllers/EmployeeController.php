<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\FileUploaderService;
use App\Http\Requests\EmployeeRequest;
use App\Http\Resources\EmployeeResource;

class EmployeeController extends Controller
{


    private $fileUploaderService;
    const IMAGES_FOLDERS = 'employees_profile';

    public function __construct(FileUploaderService $fileUploaderService)
    {
        $this->fileUploaderService = $fileUploaderService;
    }
    

    public function index(Request $request)
    {
        return EmployeeResource::collection(Employee::where('entrepreneurship_id', $request->id)->get());
    }

    
    public function store(EmployeeRequest $request)
    {
        try {
            DB::beginTransaction();

            $employee = Employee::create($request->all());

            if ($request->hasFile('image')) {
                $filePath = $this->fileUploaderService->store($request->file('image'),  self::IMAGES_FOLDERS);
                $employee->image_path = $filePath;
            }

            $employee->save();

            DB::commit();
            return (new EmployeeResource($employee))->additional(['msg' => 'Empleado creado con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }

    
    public function show(Request $request)
    {
        return new EmployeeResource(Employee::findOrFail($request->id));
    }

    
    public function update(Request $request, Employee $employee)
    {
        try {
            DB::beginTransaction();

            $employee->fill($request->all());
            $previousPath = $employee->image_path;

            if ($request->hasFile('image')) {
                $filePath = $this->fileUploaderService->update($request->file('image'), $previousPath,  self::IMAGES_FOLDERS);
                $employee->image_path = $filePath;
            }

            $employee->save();

            DB::commit();
            return (new EmployeeResource($employee))->additional(['msg' => 'Empleado actualizado con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }

   
    public function destroy(Request $request)
    {
        $employee = Employee::findOrFail($request->id);
        $employee->delete();

        return (new EmployeeResource($employee))->additional(['msg' => 'Empleado eliminado con éxito']);
    }
}
