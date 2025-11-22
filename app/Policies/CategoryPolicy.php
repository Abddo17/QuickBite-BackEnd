<?php

namespace App\Policies;

use App\Models\Utilisateur;

class CategoryPolicy
{
  public  function create(Utilisateur $user): bool
  {
    return $user->role === 'admin';
  }

  public function update(Utilisateur $user): bool
  {
    return $user->role === 'admin';
  }
  public function delete(Utilisateur $user): bool
  {
    return $user->role === 'admin';
  }
}
