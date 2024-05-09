<?php

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

if (!function_exists("success_response")) {


    /**
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     * response header for successful responses
     */
    function success_response(mixed $data = [], string $message = "Request processed successfully", int $code = Response::HTTP_OK): JsonResponse
    {
        return \response()->json([
            "message" => $message,
            "data" => $data
        ], $code);

    }

}





if (!function_exists("error_response")) {


    /**
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     * helper for returning errors with messages for the user
     */
    function error_response(mixed $data = [], string $message = "Request failed", int $code = Response::HTTP_UNPROCESSABLE_ENTITY): JsonResponse
    {
        return \response()->json([
            "message" => $message,
            "data" => $data
        ], $code);

    }

}


if (!function_exists("notfound_response")) {


    /**
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     *
     * helper for returning not found error
     */
    function notfound_response(mixed $data = [], string $message = "Not found", int $code = Response::HTTP_NOT_FOUND): JsonResponse
    {
        return \response()->json([
            "message" => $message,
            "data" => $data
        ], $code);

    }

}


