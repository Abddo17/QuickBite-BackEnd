<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

  public function register(RegisterRequest $request)
  {
    $user = Utilisateur::create([
      'username' => $request->username,
      'email' => $request->email,
      'passwordHash' => Hash::make($request->password),
      'adresse' => $request->adresse,
      'role' => 'user',
    ]);
    $token = $user->createToken('auth_token')->plainTextToken;
    return response()->json([
      'message' => 'User created.',
      'user' => $user->makeHidden('passwordHash'),
      'token' => $token,
    ], 201);
  }



  public function login(LoginRequest $request)
  {
    $user = Utilisateur::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->passwordHash)) {
      throw ValidationException::withMessages([
        'email' => ['The provided credentials are incorrect.'],
      ]);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
      'message' => 'Logged In Successfuly',
      'user' => new UserResource($user),
      'token' => $token
    ]);
  }


  public function logout(Request $request)
  {
    auth()->user()->tokens()->delete();

    return response()->json(['message' => 'Logged out']);
  }
}
