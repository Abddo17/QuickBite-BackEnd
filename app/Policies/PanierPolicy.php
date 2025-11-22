<?php

namespace App\Policies;

use App\Models\Panier;
use App\Models\Utilisateur;
use Illuminate\Auth\Access\HandlesAuthorization;

class PanierPolicy
{
  use HandlesAuthorization;

  public function viewAny(Utilisateur $user)
  {
    return in_array($user->role, ['admin', 'user']);
  }
  public function create(Utilisateur $user)
  {
    return $user->role === 'user' || $user->role === 'admin';
  }

  public function update(Utilisateur $user, Panier $panier)
  {
    return $user->userId === $panier->userId;
  }

  public function delete(Utilisateur $user, Panier $panier)
  {
    return $user->userId === $panier->userId;
  }
}
