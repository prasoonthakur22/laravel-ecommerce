<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AppBaseController extends Controller
{
    public function sendResponse($data, $message)
    {
        return Response::json([
            'data' => $data,
            'success' => true,
            'message' => $message,
        ], 202);
    }

    public function sendError($error, $code = 404)
    {
        return Response::json([
            'success' => false,
            'message' => $error,
        ], $code);
    }

    public function sendSuccess($message)
    {
        return Response::json([
            'success' => true,
            'message' => $message,
        ], 200);
    }
}
