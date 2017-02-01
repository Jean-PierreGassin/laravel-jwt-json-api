<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    /**
     * Returns a JSON compliant item(s) response
     *
     * @param array $item
     * @return Illuminate\Http\Response
     */
    public function apiResponse($items)
    {
        $items = (array) $items;

        return response()->json([
            'data' => $this->parseItems($items),
        ], 200);
    }

    /**
     * Returns a JSON comliant unauthorized response
     *
     * @param array $error
     * @return Illuminate\Http\Response
     */
    public function apiUnauthorizedResponse($errors)
    {
        $errors = (array) $errors;

        return response()->json([
            'errors' => $this->parseErrors($errors),
        ], 401);
    }

    /**
     * Returns a JSON compliant error response
     *
     * @param array $error
     * @return Illuminate\Http\Response
     */
    public function apiErrorResponse($errors)
    {
        $errors = (array) $errors;

        return response()->json([
            'errors' => $this->parseErrors($errors),
        ], 400);
    }

    /**
     * Formats a nice array of objects for our response
     *
     * @param array $items
     * @return array $responseItemsArray
     */
    public function parseItems($items)
    {
        if (empty($items)) {
            return $items;
        }

        foreach ($items as $key => $value) {
            $responseItemsArray[$key] = $items[$key];
        }

        return $responseItemsArray;
    }


    /**
     * Formats a nice array of objects for our response
     *
     * @param array $errors
     * @return array $responseErrorsArray
     */
    public function parseErrors($errors)
    {
        if (empty($items)) {
            return $items;
        }

        foreach ($errors as $key => $value) {
            $responseErrorsArray[] = (object) [
                'detail' => $value,
            ];
        }

        return $responseErrorsArray;
    }
}
