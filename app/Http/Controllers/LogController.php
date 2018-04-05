<?php

namespace App\Http\Controllers;

use App\Models\Services\LogService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogController extends Controller
{
    protected $logService;
    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }
    public function getLogList(Request $request)
    {
        try {
            // get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            $logs = $this->logService->getByPaginate($request);
            return response()->json($logs);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
