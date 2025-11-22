<?php

namespace App\Policies;

use App\Models\Commande;
use App\Models\Utilisateur;

class CommandePolicy
{
  public function viewAny(Utilisateur $user)
  {
    return in_array($user->role, ['admin', 'user']);
  }

  public function view(Utilisateur $user, Commande $commande)
  {
    return $user->userId === $commande->userId || $user->role === 'admin';
  }

  public function create(Utilisateur $user)
  {
    return $user->role === 'user' || $user->role === 'admin';
  }
  public function update(Utilisateur $user)
  {
    return $user->role === 'admin';
  }

  /*   public function delete(Utilisateur $user)
  {
    return $user->role === 'admin';
  } */
}
