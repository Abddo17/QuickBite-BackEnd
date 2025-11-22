<?php

namespace App\Policies;

use App\Models\Utilisateur;

class UsersPolicy
{
  /**
   * Determine whether the user can view any models.
   */
  public function viewAny(Utilisateur $user): bool
  {
    return $user->role === "admin";
  }

  /**
   * Determine whether the user can view the model.
   */
  public function view(Utilisateur $user, Utilisateur $targetUser): bool
  {
    return $user->role === "admin" || $user->userId === $targetUser->userId;
  }

  /**
   * Determine whether the user can create models.
   */
  public function create(Utilisateur $user): bool
  {
    return $user->role === "admin" || $user->role === "user";
  }

  /**
   * Determine whether the user can update the model.
   */
  public function update(Utilisateur $user, Utilisateur $targetUser): bool
  {
    return $user->role === "admin" || $user->userId === $targetUser->userId;
  }

  /**
   * Determine whether the user can delete the model.
   */
  public function delete(Utilisateur $user, Utilisateur $targetUser): bool
  {
    return $user->role === "admin" || $user->userId === $targetUser->userId;
  }

  /**
   * Determine whether the user can restore the model.
   */
  public function restore(Utilisateur $user): bool
  {
    return $user->role === "admin";
  }

  /**
   * Determine whether the user can permanently delete the model.
   */
  public function forceDelete(Utilisateur $user): bool
  {
    return $user->role === "admin";
  }
}
