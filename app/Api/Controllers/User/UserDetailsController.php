<?php

namespace App\Api\Controllers\User;

use Illuminate\Http\Request;
use App\Api\Controllers\ApiController;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserDetailsController extends ApiController
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if (!$user = JWTAuth::authenticate()) {
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
