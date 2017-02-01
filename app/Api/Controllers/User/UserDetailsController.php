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
            $errors[] = $this->buildErrorObject(
                'Unknown user',
                'User lookup failed (couldn\'t find user)',
                $request->path(),
                404
            );

            return $this->apiErrorResponse($errors);
        }


        $data[] = $this->buildDataObject('user', $user->toArray());

        return $this->apiResponse($data);
    }
}
