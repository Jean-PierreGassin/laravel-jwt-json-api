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
            $errors[] = $this->buildErrorObject(
                'Token expired',
                $e->getMessage(),
                $request->path(),
                500
            );
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            $errors[] = $this->buildErrorObject(
                'Token invalid',
                $e->getMessage(),
                $request->path(),
                500
            );
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            $errors[] = $this->buildErrorObject(
                'Token unknown or not found',
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
