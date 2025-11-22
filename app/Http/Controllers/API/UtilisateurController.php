<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UtilisateurController extends Controller
{
  public function index()
  {
    $this->authorize('viewAny', Utilisateur::class);
    return UserResource::collection(Utilisateur::all());
  }


  public function store(RegisterRequest $request)
  {
    $this->authorize('create', Utilisateur::class);
    $utilisateur = Utilisateur::create([
      'username' => $request['username'],
      'email' => $request['email'],
      'passwordHash' => Hash::make($request['password']),
      'adresse' => $request['adresse'] ?? null,
      'role' => $request['role'],
    ]);

    return response()->json(new UserResource($utilisateur), 201);
  }


  public function show(Utilisateur $utilisateur)
  {
    $this->authorize('view', $utilisateur);
    return new UserResource($utilisateur);
  }

  public function update(Request $request, Utilisateur $user)
  {
    $this->authorize('update', $user);
    $validated = $request->validate([
      'username' => 'required|string|max:255',
      'email' => 'required|email|unique:utilisateur,email,' . $user->userId . ',userId',
      'password' => 'nullable|string|min:6',
      'adresse' => 'nullable|string|max:255',
      'role' => 'required|in:user,admin',
    ]);

    $user->update([
      'username' => $validated['username'],
      'email' => $validated['email'],
      'adresse' => $validated['adresse'] ?? null,
      'role' => $validated['role'],
      ...(isset($validated['password']) ? ['passwordHash' => Hash::make($validated['password'])] : []),
    ]);

    return response()->json(new UserResource($user), 200);
  }

  public function destroy(Utilisateur $user)
  {
    $this->authorize('delete', $user);
    $user->delete();
    return response()->json(null, 204);
  }
}
