<?php

namespace App\Traits;

use App\Helpers\DatatableForResource;
use Exception;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

trait JsonResponseTrait
{
    public function successResponse($data, $message = "Operation Successful", $statusCode = Response::HTTP_OK): \Illuminate\Http\JsonResponse
    {
        $response = [
            "success" => true,
            "data" => $data,
            "message" => $message
        ];
        return response()->json($response, $statusCode);
    }

    public function success($message = "Operation Successful", $statusCode = Response::HTTP_OK): \Illuminate\Http\JsonResponse
    {
        $response = [
            "success" => true,
            "message" => $message
        ];
        return response()->json($response, $statusCode);
    }

    /**
     * @throws Exception
     */
    public function datatableResponse($query, string $resources, array $config = []): BinaryFileResponse|\Illuminate\Http\JsonResponse
    {
        $data = DatatableForResource::make($query, $resources, $config);

        if ($data instanceof BinaryFileResponse) {
            return $data;
        }

        $response = [
            "success" => true,
            "datatable" => $data,
            "message" => 'Data Fetched Successfully'
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function errorResponse($data = null, $message = null, $statusCode = Response::HTTP_BAD_REQUEST): \Illuminate\Http\JsonResponse
    {
        slackTheError($message);
        return response()->json([
            "success" => false,
            "message" => $message,
            "data" => $data
        ], $statusCode);
    }

    public function error($message = 'Operation Failed', $statusCode = Response::HTTP_BAD_REQUEST): \Illuminate\Http\JsonResponse
    {
        slackTheError($message);
        return response()->json([
            "success" => false,
            "message" => $message,
        ], $statusCode);
    }

    public function fatalErrorResponse(Exception $e, $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR): \Illuminate\Http\JsonResponse
    {
        $line = $e->getTrace();

        $error = [
            "message" => $e->getMessage(),
            "trace" => $line[0],
            "mini_trace" => $line[1]
        ];

        if (strtoupper(config("APP_ENV")) === "PRODUCTION") {
            $error = null;
        }
        slackTheError($e->getMessage(), $e);
        return response()->json([
            "success" => false,
            "message" => "Oops! Something went wrong on the server",
            "error" => $error
        ], $statusCode);
    }
}
