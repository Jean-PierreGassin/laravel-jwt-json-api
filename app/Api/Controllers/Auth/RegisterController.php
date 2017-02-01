<?php

namespace App\Api\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Api\Validators\AuthValidator;
use App\Api\Controllers\ApiController;

class RegisterController extends ApiController
{
    protected $validator;

    public function __construct()
    {
        $this->validator = new AuthValidator();
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return response
     */
    protected function store(Request $request)
    {
        $validator = $this->validator->register($request->input('data.attributes'));

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
            User::create([
                'name' => $request->input('data.attributes.name'),
                'email' => $request->input('data.attributes.email'),
                'password' => Hash::make($request->input('data.attributes.password')),
            ]);
        } catch (Exception $e) {
            $errors[] = $this->buildErrorObject(
                'Unknown error',
                $e->getMessage(),
                $request->path(),
                500
            );

            return $this->apiErrorResponse($errors);
        }

        return;
    }
}
