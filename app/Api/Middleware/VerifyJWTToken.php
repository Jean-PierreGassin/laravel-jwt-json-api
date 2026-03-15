<?php

namespace App\Api\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Api\Controllers\ApiController;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class VerifyJWTToken extends ApiController
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Closure  $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        $errors = [];

        try {
            JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            $errors[] = $this->buildErrorObject(
                'Authorization error',
                $e->getMessage(),
                $request->path(),
                500
            );
        }

        if (!empty($errors)) {
            return $this->apiErrorResponse($errors);
        }

        return $next($request);
    }
}
