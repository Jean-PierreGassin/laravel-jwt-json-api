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
            foreach ($validator->errors()->all() as $error) {
                $errors[] = $this->buildErrorObject(
                    'Validation failed',
                    $error,
                    $request->path(),
                    500
                );
            }

            return $this->apiErrorResponse($errors);
        }

        try {
            if (!$token = \JWTAuth::attempt($request->input('data.attributes'))) {
                $errors[] = $this->buildErrorObject(
                    'Authorization failed',
                    'Username or password is incorrect',
                    $request->path(),
                    500
                );

                return $this->apiErrorResponse($errors);
            }
        } catch (JWTException $e) {
            $errors[] = $this->buildErrorObject(
                'Unknown error',
                $e->getMessage(),
                $request->path(),
                500
            );

            return $this->apiErrorResponse($errors);
        }

        $data[] = $this->buildDataObject('authtoken', ['token' => $token]);

        return $this->apiResponse($data);
    }
}
