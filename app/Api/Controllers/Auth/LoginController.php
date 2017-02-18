<?php

namespace App\Api\Controllers\Auth;

use Illuminate\Http\Request;
use App\Api\Validators\AuthValidator;
use App\Api\Controllers\ApiController;

class LoginController extends ApiController
{
    protected $validator;

    public function __construct()
    {
        $this->validator = new AuthValidator();
    }

    /**
     * Check a users credentials and log them in.
     *
     * @param array $data
     * @return Illuminate\Http\Response
     */
    protected function store(Request $request)
    {
        $validator = $this->validator->login($request->input('data.attributes'));

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
