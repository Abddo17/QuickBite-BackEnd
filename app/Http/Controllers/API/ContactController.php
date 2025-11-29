<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
  public function send(Request $request)
  {
    $data = $request->validate([
      'name'    => 'required|string|max:100',
      'email'   => 'required|email',
      'message' => 'required|string|max:2000',
    ]);

    $html = "
            <strong>Name:</strong> {$data['name']}<br>
            <strong>Email:</strong> {$data['email']}<br><br>
            <strong>Message:</strong><br>
            {$data['message']}
        ";

    Mail::html($html, function ($msg) use ($data) {
      $msg->to(env('ADMIN_EMAIL'))
        ->subject("New Contact Form Message from {$data['name']}");
    });

    return response()->json([
      'success' => true,
      'message' => 'Message sent successfully.',
    ]);
  }
}
