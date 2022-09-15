<?php

namespace App\Http\Controllers;

use App\Models\Installation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\InstallationRequest;
use App\Http\Resources\InstallationResource;
use App\Services\FileUploaderService;

class InstallationController extends Controller
{
    
    private $fileUploaderService;
    const IMAGES_FOLDERS = 'installations_image';

    public function __construct(FileUploaderService $fileUploaderService)
    {
        $this->fileUploaderService = $fileUploaderService;
    }



    public function index(Request $request)
    {
        $installationCategories = Installation::select('category')->groupBy('category')->get();
        return (InstallationResource::collection(Installation::where('entrepreneurship_id', $request->id)->get()))->additional(['categories' => $installationCategories,  'ok' => true]);
    }


    public function store(InstallationRequest $request)
    {
        try {
            DB::beginTransaction();

            $installation = Installation::create($request->all());
            if ($request->hasFile('image')) {
                $filePath = $this->fileUploaderService->store($request->file('image'),  self::IMAGES_FOLDERS);
                $installation->image_path = $filePath;
            }
            $installation->save();

            if ($request->input('localServices')) {
                $installation->localServices()->attach($request->input('localServices'));
            }

            $installation->refresh();
            DB::commit();
            return (new InstallationResource($installation))->additional(['msg' => 'Instalación creado con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }


    public function show(Request $request)
    {
        return new InstallationResource(Installation::with('localServices')->findOrFail($request->id));
    }


    public function update(InstallationRequest $request, Installation $installation)
    {
        try {
            DB::beginTransaction();

            $installation->fill($request->all());

            $previousPath = $installation->image_path;
            if ($request->hasFile('image')) {
                $filePath = $this->fileUploaderService->update($request->file('image'), $previousPath,  self::IMAGES_FOLDERS);
                $installation->image_path = $filePath;
            }
            $installation->save();

            if ($request->input('localServices')) {
                $installation->localServices()->sync($request->input('localServices'));
            }

            
            DB::commit();
            return (new InstallationResource(Installation::with('localServices')->findOrFail($installation->id)))->additional(['msg' => 'Instalación actualizada con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }

    
    public function destroy(Request $request)
    {
        try {
            DB::beginTransaction();

            $installation = Installation::with('localServices')->findOrFail($request->id);
            $installation->localServices()->detach();
            $installation->delete();

            DB::commit();
            return (new InstallationResource($installation))->additional(['msg' => 'Instalación eliminado con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }

}
