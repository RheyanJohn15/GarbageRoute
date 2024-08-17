<?php
namespace App\Services;
use Illuminate\Http\JsonResponse;

class ApiException extends \Exception
{

    public function render(): JsonResponse
    {
        return response()->json([
            'status' => 'fail',
            'message' => $this->getMessage(),
        ], 400);
    }

    public const NOT_VALID_TYPE = 'Not a valid request type';
    public const NOT_VALID_METHOD = 'Not a valid request method';
    public const NOT_AUTHENTICATED = 'Cannot Access API not Authenticated';
    public const NO_DATA_FOUND = 'No data found in this api request';
    public const INVALID_PARAMS = 'The paramater body is incomplete or invalid';
}