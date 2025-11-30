<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\BrevoService;

class ContactController extends Controller
{
  protected $brevoService;

  public function __construct(BrevoService $brevoService)
  {
    $this->brevoService = $brevoService;
  }

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

    $adminEmail = env('ADMIN_EMAIL');

    if (!$adminEmail) {
      return response()->json([
        'success' => false,
        'message' => 'Admin email is not configured.',
      ], 500);
    }

    try {
      $this->brevoService->sendEmail(
        $adminEmail,
        'Admin',
        "New Contact Form Message from {$data['name']}",
        $html
      );

      return response()->json([
        'success' => true,
        'message' => 'Message sent successfully.',
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to send message. Please try again later.',
      ], 500);
    }
  }
}
