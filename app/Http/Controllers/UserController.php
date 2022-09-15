<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Services\FileUploaderService;

class UserController extends Controller
{

    private $fileUploaderService;
    const IMAGES_FOLDERS = 'users_profile';

    public function __construct(FileUploaderService $fileUploaderService)
    {
        $this->fileUploaderService = $fileUploaderService;
    }

    public function index()
    {
        return UserResource::collection(User::with('entrepreneurship')->get());
    }

    public function store(UserRequest $request)
    {

        try {
            DB::beginTransaction();

            $user = User::create($request->all());
            $user->password = Hash::make($request->password);

            if ($request->hasFile('image')) {
                $filePath = $this->fileUploaderService->store($request->file('image'),  self::IMAGES_FOLDERS);
                $user->image_path = $filePath;
            }

            $user->save();

            $request->type === 'admin'
                ? $user->assignRole('admin')
                : $user->assignRole('client');

            DB::commit();
            return (new UserResource($user))->additional(['msg' => 'Usuario creado con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }

    public function show(Request $request)
    {
        return new UserResource(User::with('entrepreneurship')->findOrFail($request->id));
    }

    public function update(UserRequest $request, User $user)
    {
        try {
            DB::beginTransaction();

            $userAuth = auth()->user()->roles->first();
            $user->fill([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'email'    => $request->email,
                'type'     => $request->type,
                'entrepreneurship_id' => $request->entrepreneurship_id,
            ]);

            $previousPath = $user->image_path;
            if ($request->hasFile('image')) {
                $filePath = $this->fileUploaderService->update($request->file('image'), $previousPath,  self::IMAGES_FOLDERS);
                $user->image_path = $filePath;
            }

            $user->save();

            if ($request->type && $userAuth->name === 'admin') {
                $role = $user->getRoleNames()->first();
                $user->removeRole($role);
                $user->assignRole($request->type);
            }

            DB::commit();
            return (new UserResource($user))->additional(['msg' => 'Usuario actualizado con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->delete();

        return (new UserResource($user))->additional(['msg' => 'Usuario eliminado con éxito']);
    }
}
