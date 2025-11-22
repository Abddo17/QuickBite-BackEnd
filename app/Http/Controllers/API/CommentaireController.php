<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Commentaire;
use Illuminate\Http\Request;

class CommentaireController extends Controller
{

  public function index()
  {
    return Commentaire::with(['utilisateur', 'product'])->get();
  }


  public function store(Request $request)
  {
    $this->authorize('create', Commentaire::class);
    $request->validate([
      'produitId' => 'required|exists:products,produitId',
      'content' => 'required|string',
      'rating' => 'nullable|integer|between:1,5',
    ]);

    $commentaire = Commentaire::create([
      'userId' => $request->user()->userId,
      'produitId' => $request->produitId,
      'content' => $request->content,
      'rating' => $request->rating,
    ]);

    return response()->json($commentaire->load(['utilisateur', 'product']), 201);
  }



  public function show(Commentaire $commentaire)
  {
    return $commentaire->load(['utilisateur', 'product']);
  }



  public function update(Request $request, Commentaire $commentaire)
  {
    $this->authorize('update', $commentaire);
    $request->validate([
      'content' => 'required|string',
      'rating' => 'nullable|integer|between:1,5',
    ]);

    $commentaire->update($request->only(['content', 'rating']));
    return response()->json($commentaire->load(['utilisateur', 'product']));
  }



  public function destroy(Commentaire $commentaire)
  {
    $this->authorize('delete', $commentaire);
    $commentaire->delete();
    return response()->json(null, 204);
  }
}
