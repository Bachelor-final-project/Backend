<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Currency;
use App\Models\Proposal;

class StripeM extends BaseModel
{
    use HasFactory;

    public static function doPayment($items, $donationIds, $donatingUrl) {
        Stripe::setApiKey(config('stripe.test_sk'));

        $lineItems = [];
        foreach($items as $item) {
            $currencyCode = Currency::find($item['currency_id'])->code ?? 'usd';
            $proposalTitle = Proposal::find($item['proposal_id'])->title ?? 'donation';
            $lineItems[] = [
                'price_data' => [
                    'currency' => $currencyCode,
                    'product_data' => [
                        'name' => $proposalTitle
                    ],
                    'unit_amount' => $item['amount'] * 100,
                ],
                'quantity' => 1,
            ];
        }
        // dd($lineItems);
        $param = http_build_query([
            'donation_ids' => $donationIds,
            'donating_form_url' => $donatingUrl 
        ]);
        // dd($param);
        $session = Session::create([
            'line_items'  => $lineItems,
            'mode'        => 'payment',
            'success_url' => route('success') . '?' . $param,
            'cancel_url'  => route('checkout')  . '?' . $param,
        ]);

        return $session->url;

    }
}
