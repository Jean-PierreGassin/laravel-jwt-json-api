<?php

namespace App\Api\Controllers\User;

use App\Api\Controllers\ApiController;

class UserDetailsController extends ApiController
{
    /**
     * Retrieve the currently authenticated user.
     *
     * @return User
     */
    protected function retrieve()
    {
        if (!$user = \JWTAuth::authenticate()) {
            $response = $this->apiErrorResponse('User not found');

            return $response;
        }

        $response = $this->apiResponse($user->all()->toArray());

        return $response;
    }
}
