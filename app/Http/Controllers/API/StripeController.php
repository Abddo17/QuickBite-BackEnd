<?php

namespace App\Http\Controllers\API;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use ErrorException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StripeController extends Controller
{


  public function PayByStripe(Request $request)
  {   // setting the api key from stripe
    Stripe::setApiKey(env('STRIPE_SECRET'));
    try {
      // creating paymentIntent
      $paymentIntent = PaymentIntent::create([
        'amount' => $request->amount,
        'currency' => 'usd',
        'automatic_payment_methods' => ['enabled' => true],
      ]);

      return response()->json([
        'clientSecret' => $paymentIntent->client_secret,
      ]);
    } catch (ErrorException $e) {
      return response()->json(['error' => $e->getMessage()]);
    }
  }
}
