<?php

namespace App\Policies;

use App\Models\Commentaire;
use App\Models\Utilisateur;

class CommentairePolicy
{
  public function create(Utilisateur $user)
  {
    return $user->role === 'user';
  }
  public function update(Utilisateur $user, Commentaire $commentaire)
  {
    return $user->userId === $commentaire->userId;
  }

  public function delete(Utilisateur $user, Commentaire $commentaire)
  {
    return $user->userId === $commentaire->userId;
  }
}
