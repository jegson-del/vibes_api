<?php

namespace App\Http\Middleware;

use App\Http\Resources\StatusResponse;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class AdminGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->tokenCan('admin') || $this->multiLevelTokenCan($request->user()->tokens)) {
            return $next($request);
        }

        return new StatusResponse(
            'error',
            ['message' => 'Authorisation Error']
        );
    }

    public function multiLevelTokenCan(object $tokens): bool
    {
        foreach ($tokens as $token) {
            if (in_array('admin', $token->abilities)) {
                return true;
            }
        }

        return false;
    }
}
