<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Services\TaskMappingService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class TaskMappingController extends Controller
{
    protected $taskMappingService;

    public function __construct(TaskMappingService $taskMappingService)
    {
        $this->taskMappingService = $taskMappingService;
    }

    public function getTaskMappingList(Request $request)
    {
        try {
            // get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            $taskMappings = $this->taskMappingService->getByPaginate($request);
            return response()->json($taskMappings);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function update(Request $request)
    {
        $rules = [
            'type' => 'required',
            'location.id' => 'required',
            'activity.id'  =>  'required',
            'task_name'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $taskMapping = $this->taskMappingService->update($request);
            $this->taskMappingService->LogEntity($taskMapping, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('taskMapping'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->taskMappingService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
}
