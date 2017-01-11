<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    /**
     * Get a validator for an incoming login request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
    }

    /**
     * Check a users credentials and log them in.
     *
     * @param  array  $data
     * @return response
     */
    protected function login(Request $request)
    {
        $validator = $this->validator($request->all());
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        
        try {
            if (!$token = \JWTAuth::attempt($request->all())) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return response()->json(compact('token'));
    }
}
