<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
  public function subscribe(Request $request)
  {
    $data = $request->validate([
      'email' => 'required|email',
    ]);

    $html = "<p>New newsletter subscription:</p>
                 <p>Email: {$data['email']}</p>";

    Mail::html($html, function ($msg) use ($data) {
      $msg->to(env('ADMIN_EMAIL'))
        ->subject("New Newsletter Subscription");
    });

    return response()->json([
      'success' => true,
      'message' => 'Thank you! You have been subscribed.'
    ]);
  }
}
