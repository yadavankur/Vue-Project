<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Services\NoteTypeActionService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class NoteTypeActionController extends Controller
{
    protected $noteTypeActionService;

    public function __construct(NoteTypeActionService $noteTypeActionService)
    {
        $this->noteTypeActionService = $noteTypeActionService;
    }

    public function getNoteTypeActionList(Request $request)
    {
        try {
            // get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            $noteTypeActions = $this->noteTypeActionService->getByPaginate($request);
            return response()->json($noteTypeActions);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function update(Request $request)
    {
        $rules = [
            'type' => 'required',
            'location_id'  =>  'required',
            'note_type_id'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $noteTypeAction = $this->noteTypeActionService->update($request);
            $this->noteTypeActionService->LogEntity($noteTypeAction, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('noteTypeAction'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->noteTypeActionService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
}
