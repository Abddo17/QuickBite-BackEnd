<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BrevoService;

class NewsletterController extends Controller
{
  protected $brevoService;

  public function __construct(BrevoService $brevoService)
  {
    $this->brevoService = $brevoService;
  }

  public function subscribe(Request $request)
  {
    $data = $request->validate([
      'email' => 'required|email',
    ]);

    $html = "<p>New newsletter subscription:</p>
                 <p>Email: {$data['email']}</p>";

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
        "New Newsletter Subscription",
        $html
      );

      return response()->json([
        'success' => true,
        'message' => 'Thank you! You have been subscribed.'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to subscribe. Please try again later.',
      ], 500);
    }
  }
}
