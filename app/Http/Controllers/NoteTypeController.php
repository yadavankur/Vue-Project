<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Services\NoteTypeService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class NoteTypeController extends Controller
{
    protected $noteTypeService;

    public function __construct(NoteTypeService $noteTypeService)
    {
        $this->noteTypeService = $noteTypeService;
    }
    public function getNoteTypeList(Request $request)
    {
        try {
            // get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            $noteTypes = $this->noteTypeService->getByPaginate($request);
            return response()->json($noteTypes);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function update(Request $request)
    {
        $rules = [
            'type' => 'required',
            'name'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $noteType = $this->noteTypeService->update($request);
            $this->noteTypeService->LogEntity($noteType, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('noteType'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->noteTypeService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function getNoteTypeOptions(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get the latest notes
            $noteTypeOptions = $this->noteTypeService->getNoteTypeOptions($request);
            return response()->json($noteTypeOptions);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getDowellNoteTypeOptions(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get the latest notes
            $noteTypeOptions = $this->noteTypeService->getDowellNoteTypeOptions($request);
            return response()->json($noteTypeOptions);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
