<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommandeController extends Controller
{
  public function index(Request $request)
  {
    $this->authorize('viewAny', Commande::class);
    $user = $request->user();

    if ($user->role === 'admin') {
      return Commande::with('orderItems.product')->get();
    }

    return $user->commandes()->with('orderItems.product')->get();
  }

  public function store(Request $request)
  {
    $this->authorize('create', Commande::class);
    $user = $request->user();
    $cartItems = $user->paniers()->with('product')->get();

    if ($cartItems->isEmpty()) {
      return response()->json(['message' => 'Cart is empty'], 400);
    }

    $totalPrix = $cartItems->sum(function ($item) {
      return $item->quantite * $item->product->prix;
    });

    DB::beginTransaction();
    try {
      $commande = Commande::create([
        'userId' => $user->userId,
        'totalPrix' => $totalPrix,
        'stat' => 'pending',
      ]);

      foreach ($cartItems as $item) {
        OrderItem::create([
          'commandeId' => $commande->commandeId,
          'produitId' => $item->produitId,
          'quantite' => $item->quantite,
          'prix' => $item->product->prix,
        ]);

        $product = Product::find($item->produitId);
        $product->stock -= $item->quantite;
        $product->save();
      }

      $user->paniers()->delete();
      DB::commit();

      return response()->json($commande->load('orderItems.product'), 201);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json(['message' => 'Order creation failed'], 500);
    }
  }

  public function show(Commande $commande)
  {
    $this->authorize('view', $commande);
    return $commande->load('orderItems.product');
  }


  public function update(Request $request, Commande $commande)
  {;
    $this->authorize('update', $commande);
    // Validate the status
    $validated = $request->validate([
      'stat' => 'required|in:pending,processing,shipped,delivered,cancelled',
    ]);

    // Update the order status
    $commande->stat = $validated['stat'];
    $commande->save();

    // Return the updated order with related data
    return response()->json($commande->load('orderItems.product'), 200);
  }
}
