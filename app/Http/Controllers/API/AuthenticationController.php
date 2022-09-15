<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginApiRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;



class AuthenticationController extends Controller
{


    public function login(LoginApiRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'msg' => ['Las credenciales ingresadas son incorrectas.'],
            ]);
        }

        $token = $user->createToken($user->email."_".$user->id)->plainTextToken;

        return (new UserResource($user))->additional(['token' => $token]);
    }


    public function logout(Request $request) {

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'ok' => true,
            'msg' => 'Sesión cerrada con éxito',
        ], 200);
    }

}
