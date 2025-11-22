<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CommentaireController;
use App\Http\Controllers\API\CommandeController;
use App\Http\Controllers\API\UtilisateurController;
use App\Http\Controllers\API\PanierController;
use App\Http\Controllers\API\StripeController;
use App\Http\Resources\UserResource;


// Authentication route
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Products route (does not need authentication )
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show']);

// all ressource route for products comments
Route::get('/commentaires', [CommentaireController::class, 'index']);
Route::get('/commentaires/{commentaire}', [CommentaireController::class, 'show']);

// all ressource route for ctegory comments
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{categorie}', [CategoryController::class, 'show']);


// All the route that needs authentication
Route::middleware('auth:sanctum')->group(function () {
  // logout route
  Route::post('/logout', [AuthController::class, 'logout']);
  // a simple route that returns the current logged in user
  Route::get('/user', function (Request $request) {
    return response()->json([
      'user' => new UserResource($request->user())
    ]);
  });
  Route::apiResource('users', UtilisateurController::class);
  // products,comments route ressource (store,update ,destroy)
  Route::apiResource('products', ProductController::class)->except(['show', 'index']);
  Route::apiResource('commentaires', CommentaireController::class)->only(['store', 'update', 'destroy']);
  // route for products categories
  Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);

  // cart route ressource (index,store,update ,destroy)
  Route::apiResource('panier', PanierController::class);
  // commands route (index,store,show)
  Route::apiResource('commandes', CommandeController::class);
  // Stripe Route (for payment integration)
  Route::post('stripe/pay', [StripeController::class, 'PayByStripe']);
});
