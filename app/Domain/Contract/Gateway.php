<?php

namespace App\Domain\Contract;

use App\Http\Requests\PaymentRequest;

interface Gateway
{
    public function charge(string $amount, string $token);
}
