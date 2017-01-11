<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class UserDetailsController extends Controller
{
    /**
     * Retrieve the currently authenticated user.
     *
     * @return User
     */
    protected function retrieve()
    {
        if (!$user = \JWTAuth::authenticate()) {
            return response()->json(['error' => 'User not found']);
        }
        
        return $user;
    }
}
