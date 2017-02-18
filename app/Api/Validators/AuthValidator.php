<?php

namespace App\Api\Validators;

use Validator;

class AuthValidator
{
    /**
     * Validate an incoming registration request
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function register(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);
    }

    /**
     * Validate an incoming login request
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function login(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
    }
}
