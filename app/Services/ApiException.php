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
    public const INVALID_BODY = 'The request body is incomplete or invalid';
    public const USERNAME_EXIST = 'The username already exist';
    public const SAME_PASS = 'The new password is the same with the old password';
    public const CURRENT_PASS_INVALID = 'Current Password does not match, not authenticated';
    public const INVALID_PIC_TYPE = 'Invalid Image type use (.png, .jpg, .jpeg)';
    public const LARGE_IMAGE = "File is too large below 10mb is valid";
    public const NO_ZONE_ASSIGNED = "You are not assigned in any zone";
    public const DATA_EXIST = "Data already exist duplication is not allowed";
    public const NO_IMAGE = "You have not attach any complaint image. Please Attach one!";
}