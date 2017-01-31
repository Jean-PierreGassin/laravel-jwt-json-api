<?php

namespace App\Api\Controllers\Auth;

use App\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Api\Controllers\ApiController;

class RegisterController extends ApiController
{
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return response
     */
    protected function create(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $validatorErrors = $validator->errors()->all();

            $response = $this->apiErrorResponse($validatorErrors);

            return $response;
        }

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
        } catch (Exception $e) {
            $response = $this->apiErrorResponse($e->getMessage());

            return $response;
        }

        $response = $this->apiResponse('User created');

        return $response;
    }
}
