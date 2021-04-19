<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\VerificationController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\UserEventController;
use App\Http\Controllers\Api\UserBlockEventController;

#auth
Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/forgot', [AuthController::class, 'forgot'])->name('api.forgot');

//Route::get('email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::get('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::delete('/delete_account', [AuthController::class, 'deleteAccount'])->name('api.delete_account');
});
#end auth

#user
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user/detail', [UserController::class, 'getUser'])->name('api.user.detail');
    Route::post('user/upload_profile_pics', [UserController::class, 'uploadProfilePics'])
        ->name('api.user.profile_pics');
});
#end user

#event
Route::get('events', [EventController::class, 'index'])->name('api.events.index');
Route::get('events/search/{keyword}', [EventController::class, 'search'])->name('api.events.search');
Route::get('events/top_locations', [EventController::class, 'topLocations'])->name('api.events.top_locations');
Route::get('events/single/{event}', [EventController::class, 'show'])->name('api.events.show');
#endevent

#user events
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user/events/my_events', [UserEventController::class, 'index'])->name('api.user.events.index');
    Route::get('user/events/{event}', [UserEventController::class, 'show'])->name('api.user.events.show');
});
#end user events

#user block event
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user/block/see_events', [UserBlockEventController::class, 'index'])->name('api.block.see_events');
    Route::post('user/block/block_event', [UserBlockEventController::class, 'store'])->name('api.block.block_event');
    Route::delete('user/block/unblock_event/{event}', [UserBlockEventController::class, 'destroy'])
        ->name('api.block.unblock_event');
});
#end user block event

#payment
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/payment/charge', [PaymentController::class, 'charge'])->name('api.payment.charge');
});
#endpayment

#Ticket
Route::get('ticket/{ticket}', [TicketController::class, 'show'])->name('api.ticket.show');

Route::middleware('auth:sanctum')->group(function () {
    Route::patch('ticket/update_owner', [TicketController::class, 'updateOwner'])->name('api.ticket.owner');
});
#EndTicket
