<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Donation;

class StripeController extends Controller
{
    public function checkout()
    {
        $donationsIds = request()->query('donation_ids');
        Donation::whereIn('id', $donationsIds)->update(['status' => 3]);

        $donatingFormUrl = request()->query('donating_form_url');
        return Inertia::render('ShowPaymentResult', ['status' => 'fail']);
        // return back()->with('res', ['message' => __('Payment Cancelled'), 'type' => 'error']);
    }

    /**
     * @return RedirectResponse
     * @throws ApiErrorException
     */
    public function test(): RedirectResponse
    {
        Stripe::setApiKey(config('stripe.test.sk'));

        $session = Session::create([
            'line_items'  => [
                [
                    'price_data' => [
                        'currency'     => 'gbp',
                        'product_data' => [
                            'name' => 'T-shirt',
                        ],
                        'unit_amount'  => 500,
                    ],
                    'quantity'   => 1,
                ],
            ],
            'mode'        => 'payment',
            'success_url' => route('success'),
            'cancel_url'  => route('checkout'),
        ]);

        return redirect()->away($session->url);
    }

    /**
     * @return View|Factory|Application
     */
    public function success()
    {
        $donationsIds = request()->query('donation_ids');
        Donation::whereIn('id', $donationsIds)->update(['status' => 2]);

        $donatingFormUrl = request()->query('donating_form_url');
        return Inertia::render('ShowPaymentResult', ['status' => 'success']);
        // return back()->with('res', ['message' => __('Payment Completed Successfully'), 'type' => 'success']);
    }
}
