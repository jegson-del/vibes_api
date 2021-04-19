<?php

namespace App\Http\Controllers\Api;

use App\Domain\Payment\StripeGateway;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResponse;
use App\Models\Event;
use App\Models\Payment;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    private StripeGateway $gateway;

    public function __construct(StripeGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function charge(PaymentRequest $request)
    {
        $event = Event::findOrFail($request->event_id);

        $paymentTypeAndQuantity = $request->payment_type_and_quantity;

        $amount = 0;

        foreach($event->prices as $price) {
            if(isset($paymentTypeAndQuantity[$price->id])) {
                $amount += $paymentTypeAndQuantity[$price->id] * $price->pivot->price;
            }
        }

        $payment = $amount > 0
            ? $tickets = $this->createMultipleTickets($amount, $request, $paymentTypeAndQuantity)
            : $tickets = $this->createSingleTicket($request);

        return new PaymentResponse($payment);
    }

    private function createSingleTicket(PaymentRequest $request): Payment
    {
        $payment = (new Payment())->store($request, ['id' => Payment::FREE_PAYMENT_NAME], 0);

        Ticket::create([
            'id' => Str::uuid(),
            'payment_id' => $payment->id,
            'event_id' => $payment->event_id,
            'price_type_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $payment->load('tickets');
    }

    private function createMultipleTickets(float $amount, PaymentRequest $request, array $paymentTypeAndQuantity): Payment
    {
        $paymentData = $this->gateway->charge($amount, $request->token);

        $payment = (new Payment())->store($request, $paymentData, $amount);

        $tickets = [];

        foreach($paymentTypeAndQuantity as $priceType=>$quantity) {
            for ($i = 0; $i < $quantity; $i++) {
                $tickets[] = [
                    'id' => Str::uuid(),
                    'payment_id' => $payment->id,
                    'event_id' => $payment->event_id,
                    'price_type_id' => $priceType,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Ticket::insert($tickets);

        return $payment->load('tickets');
    }
}
