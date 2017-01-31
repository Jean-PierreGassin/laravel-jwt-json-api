<?php

namespace App\Api\Middleware;

use Closure;
use App\Api\Controllers\ApiController;
use Tymon\JWTAuth\Exceptions\JWTException;

class VerifyJWTToken extends ApiController
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        try {
            \JWTAuth::parseToken()->authenticate();
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            $response = $this->apiErrorResponse('Token has expired');

            return $response;
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            $response = $this->apiErrorResponse('Token is invalid');

            return $response;
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            $response = $this->apiErrorResponse('Token unknown or not found');

            return $response;
        }

        return $next($request);
    }
}
