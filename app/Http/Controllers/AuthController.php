<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(UserRequest $request)
    {
        $validatedData = $request->validated();
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        event((new UserRegistered($user)));

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
        ], Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();

        $credentials = [
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
        ];

        try {
            if(! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'error' => 'Credenciales inválidas',
                ], Response::HTTP_UNAUTHORIZED);

            }

        } catch (JWTException $e) {
            return response()->json([
                'error' => 'No se pudo crear el token',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->respondWithToken($token);
    }

    public function who(){
        $user = auth()->user();
        return response()->json($user, Response::HTTP_OK);
    }

    public function logout()
    {
        try {
            $token = JWTAuth::getToken();

            if ($token) {
                JWTAuth::invalidate($token);
                return response()->json([
                    'message' => 'Sesión cerrada exitosamente',
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'error' => 'No se proporcionó un token válido',
                ], Response::HTTP_BAD_REQUEST);
            }
            
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'No se pudo cerrar la sesión',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function refresh()
    {
        try {
            $token = JWTAuth::getToken();
            $newToken = JWTAuth::refresh($token);
            JWTAuth::invalidate($token);
            return $this->respondWithToken($newToken);
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'No se pudo refrescar el token',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ], Response::HTTP_OK);
    }
}
