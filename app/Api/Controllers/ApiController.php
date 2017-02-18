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

        $response = $this->parseItems($items);

        return response()->json([
            'data' => $this->parseItems($items),
        ], 200);
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

        $response = $this->parseErrors($errors);

        return response()->json([
            'errors' => $response,
        ], $response[0]->status);
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

        foreach ($items as $item) {
            $responseObject = new \stdClass();
            $objectErrors = $this->objectHasValidProperties($item, 'data');

            $responseItemsArray[] = $item;
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
        if (empty($errors)) {
            return $errors;
        }

        $responseErrorsArray = [];

        foreach ($errors as $error) {
            $responseObject = new \stdClass();
            $objectErrors = $this->objectHasValidProperties($error, 'error');

            $responseErrorsArray[] = $error;
        }

        return $responseErrorsArray;
    }

    /**
     * Validates api response objects to make sure they contain correct properties
     *
     * @param object $object
     * @return bool
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
     * Builds data objects
     *
     * @param string $type
     * @param string/array $attributes
     * @return stdClass
     */
    public function buildDataObject($type, $attributes)
    {
        $attributes = (array) $attributes;
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
     * Builds error objects
     *
     * @param string $title
     * @param string $error
     * @param string $path
     * @param integer $statusCode
     * @return stdClass
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
