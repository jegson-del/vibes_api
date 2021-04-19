<?php

namespace App\Domain\Payment;

use App\Domain\Contract\Gateway;
use Omnipay\Omnipay;

class StripeGateway implements Gateway
{
    public function charge(string $amount, string $token): array
    {
        $gateway = Omnipay::create('Stripe');
        $gateway->setApiKey(config('payment.stripe_secret_key'));

        $response = $gateway->purchase([
            'amount' => $amount,
            'currency' => config('payment.stripe_currency'),
            'token' => $token,
        ])->send();

        throw_unless($response->isSuccessful(), new \Exception($response->getMessage()));

        return $response->getData();
    }
}
