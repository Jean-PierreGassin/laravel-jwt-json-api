<?php

namespace App\Api\Controllers\Auth;

use Validator;
use Illuminate\Http\Request;
use App\Api\Controllers\ApiController;

class LoginController extends ApiController
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
        $validator = $this->validator($request->input('data.attributes'));

        if ($validator->fails()) {
            $response = $this->apiErrorResponse($validator->errors());

            return $response;
        }

        try {
            if (!$token = \JWTAuth::attempt($request->input('data.attributes'))) {
                $response = $this->apiErrorResponse('Invalid credentials');

                return $response;
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        $response = $this->apiResponse($token);

        return $response;
    }
}
