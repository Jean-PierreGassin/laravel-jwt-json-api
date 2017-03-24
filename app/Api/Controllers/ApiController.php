<?php

namespace App\Api\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fourOhFour(Request $request)
    {
        $errors[] = $this->buildErrorObject(
            'Four oh Four',
            'I couldn\'t seem to find this route...',
            $request->path(),
            404
        );

        return $this->apiErrorResponse($errors);
    }

    /**
     * @param $items
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiResponse($items)
    {
        $items = (array)$items;

        return response()->json([
            'data' => $this->parseItems($items),
        ], 200);
    }


    /**
     * @param $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiErrorResponse($errors)
    {
        $errors = (array)$errors;

        $response = $this->parseErrors($errors);

        return response()->json([
            'errors' => $response,
        ], $response[0]->status);
    }


    /**
     * @param $items
     * @return array
     */
    public function parseItems($items)
    {
        if (empty($items)) {
            return $items;
        }

        $responseItemsArray = [];

        foreach ($items as $item) {
            $responseItemsArray[] = $item;
        }

        return $responseItemsArray;
    }


    /**
     * @param $errors
     * @return array
     */
    public function parseErrors($errors)
    {
        if (empty($errors)) {
            return $errors;
        }

        $responseErrorsArray = [];

        foreach ($errors as $error) {
            $responseErrorsArray[] = $error;
        }

        return $responseErrorsArray;
    }


    /**
     * @param $object
     * @param $type
     * @return bool|\stdClass
     */
    public function objectHasValidProperties($object, $type)
    {
        $missingProperties = [];
        $dataProperties = ['type', 'attributes'];
        $errorProperties = ['status', 'source', 'title', 'detail'];

        if ($type == 'data') {
            foreach ($dataProperties as $property) {
                if (!property_exists($object, $property)) {
                    $missingProperties[] = $property;
                }
            }
        }

        if ($type == 'error') {
            foreach ($errorProperties as $property) {
                if (!property_exists($object, $property)) {
                    $missingProperties[] = $property;
                }
            }
        }

        if (empty($missingProperties)) {
            return true;
        }

        $responseObject = new \stdClass();
        $responseObject->status = 500;
        $responseObject->source = 'ApiController.php';
        $responseObject->title = 'Invalid error object';
        $responseObject->detail = 'Missing properties: ' . implode(', ', $missingProperties);

        return $responseObject;
    }


    /**
     * @param $type
     * @param $attributes
     * @return \stdClass
     */
    public function buildDataObject($type, $attributes)
    {
        $attributes = (array)$attributes;
        $attributesObject = new \stdClass();

        foreach ($attributes as $key => $value) {
            $attributesObject->{$key} = $value;
        }

        $dataObject = new \stdClass();
        $dataObject->type = $type;
        $dataObject->attributes = $attributesObject;

        return $dataObject;
    }


    /**
     * @param $title
     * @param $error
     * @param $path
     * @param int $statusCode
     * @return \stdClass
     */
    public function buildErrorObject($title, $error, $path, $statusCode = 500)
    {
        $errorObject = new \stdClass();
        $errorObject->title = $title;
        $errorObject->detail = $error;
        $errorObject->source = $path;
        $errorObject->status = $statusCode;

        return $errorObject;
    }
}
