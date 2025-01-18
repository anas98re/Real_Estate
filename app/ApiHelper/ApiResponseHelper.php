<?php

namespace App\ApiHelper;

use Hamcrest\Arrays\IsArray;

class ApiResponseHelper
{
    public static function sendResponsePaginate($data ,$perPage)
    {
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage('page');

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $data->forPage($currentPage, $perPage),
            $data->count(),
            $perPage,
            $currentPage,
            [
                'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
                'pageName' => 'page',
            ]
        );

        return $paginator;
    }
    public static function sendResponseNew($result, $message)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message
        ];
        return response()->json($response, 200);
    }

    public static function sendResponseCollection($result, $message)
    {
        $response = [
            'total_rows' => $result->count(),
            'success' => true,
            'data' => $result,
            'message' => $message
        ];
        return response()->json($response, 200);
    }

    public static function sendSucssas($result)
    {
        $response = [
            'result' => 'success',
            'code' => 200,
            'message' => $result
        ];
        return response()->json($response, 200);
    }

    public static function Unauthenticated($result)
    {
        $response = [
            'result' => 'success',
            'code' => 200,
            'message' => $result
        ];
        return response()->json($response, 200);
    }

    public static function sendError($error, $errorMessage = [], $code = 200)
    {
        $response = [
            'success' => false,
            'message' => $error
        ];
        if (!empty($errorMessage)) {
            $response['data'] = $errorMessage;
        }
        return response()->json($response, $code);
    }

    public static function sendResponse(Result $result, int $statusCode = ApiResponseCodes::SUCCESS)
    {
        return \Response::json([
            'success' => $result->isOk,
            'message' => $result->message,
            'data' => $result->result ?? null,
        ], $statusCode);
    }

    public static function sendResponseWithPagination(Result $response)
    {
        return \Response::json([
            'success' => $response->isOk,
            'message' => $response->message,
            'pagination' => $response->paginate ?? null,
            'data' => $response->result ?? null,
        ], ApiResponseCodes::SUCCESS);
    }

    public static function sendSuccessResponse(SuccessResult $response)
    {
        return \Response::json([
            'success' => $response->isOk,
            'error_code' => null,
            'message' => $response->message,
            'data' => null,
            'paginate' => null,
        ], ApiResponseCodes::SUCCESS);
    }

    public static function sendErrorResponse(ErrorResult $response, $errorCode = 0)
    {
        return \Response::json([
            'success' => $response->isOk,
            'error_code' => $response->errorCode,
            'message' => $response->message,
            'data' => $response,
            'paginate' => null,
        ], $errorCode);
    }

    public static function sendResponse2($result, $message, $code = 200, $errorCode = 0)
    {
        return \Response::json([
            'status' => $code,
            'errorCode' => $errorCode,
            'data' => $result,
            'message' => $message,
        ], $code);
    }

    public static function sendMessageResponse($message, $code = 200, $success = true)
    {
        return \Response::json([
            'success' => $success,
            'message' => $message,
        ], $code);
    }

    public static function sendResponseWithCount(Result $response, $count)
    {
        return \Response::json([
            'success' => $response->isOk,
            'message' => $response->message,
            'count' => $count,
            'data' => $response->result ?? null,
        ], ApiResponseCodes::SUCCESS);
    }

    public static function sendResponseWithKey(Result $response, $array)
    {
        return \Response::json(array_merge($array, [
            'success' => $response->isOk,
            'message' => $response->message,
            'data' => $response->result ?? null,
        ]), ApiResponseCodes::SUCCESS);
    }

    public static function sendResponseOnlyKey($array, $success = true, $message = 'Done', $status = ApiResponseCodes::SUCCESS)
    {
        return \Response::json(array_merge($array, [
            'success' => $success,
            'message' => $message,
        ]), $status);
    }
}
