<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Panier;
use App\Models\Commande;
use App\Models\Commentaire;
use App\Models\Product;
use App\Models\Utilisateur;
use App\Policies\CategoryPolicy;
use App\Policies\PanierPolicy;
use App\Policies\CommandePolicy;
use App\Policies\CommentairePolicy;
use App\Policies\ProductPolicy;
use App\Policies\UsersPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
  protected $policies = [
    Category::class => CategoryPolicy::class,
    Panier::class => PanierPolicy::class,
    Commande::class => CommandePolicy::class,
    Commentaire::class => CommentairePolicy::class,
    Product::class => ProductPolicy::class,
    Utilisateur::class => UsersPolicy::class,
  ];

  public function boot()
  {
    $this->registerPolicies();
  }
}
