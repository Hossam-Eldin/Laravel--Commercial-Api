<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponser;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Closure;

class CustomThrottleRequests
{
    use ApiResponser;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function buildResponses($key, $maxAttempts)
    {
        $response = $this->errorResponse('Too Many Attempts', 429);
        $retryAfter = $this->limiter->availabeIn($key);

        return $this->addHeaders(
            $response, $maxAttempts,
            $this->calculateRemainingAttempts($key , $maxAttempts, $retryAfter),
            $retryAfter
        );
    }
}
