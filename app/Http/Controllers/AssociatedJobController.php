<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Services\AssociatedJobService;
use App\Models\Services\UserService;

use Barryvdh\Snappy\Facades\SnappyPdf;
use Exception;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\View;
use PDF;
use Tymon\JWTAuth\Facades\JWTAuth;

class AssociatedJobController extends Controller
{
    protected $associatedJobService;
    protected $userService;

    public function __construct(AssociatedJobService $associatedJobService,
                                UserService $userService)
    {
        $this->associatedJobService = $associatedJobService;
        $this->userService = $userService;
    }
    public function getAssociatedJobs(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get the associated jobs
            $associatedJobs= $this->associatedJobService->getByPaginate($request);
            return response()->json($associatedJobs);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getAllAssociatedJobs(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get the associated jobs
            $associatedJobs= $this->associatedJobService->getAllByPaginate($request);
            return response()->json($associatedJobs);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function addAssociatedJob(Request $request)
    {
        $rules = [
            'orderId'  =>  'required',
            'quoteId'  =>  'required',
            'location'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $associatedJob = $this->associatedJobService->add($request);
            $this->associatedJobService->LogEntity($associatedJob, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('associatedJob'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->associatedJobService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function updateAssociatedJob(Request $request)
    {
        $rules = [
            //'id' => 'required',
            'quoteId'  =>  'required',
            'orderId' => 'required',
            'newAgreedDate' => 'required'
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $associatedJob = $this->associatedJobService->update($request);
            $this->associatedJobService->LogEntity($associatedJob, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('associatedJob'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->associatedJobService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function deleteAssociatedJob(Request $request)
    {
        $rules = [
            'id'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $associatedJob = $this->associatedJobService->delete($request);
            $this->associatedJobService->LogEntity($associatedJob, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('associatedJob'));
        }
        catch (Exception $e)
        {
            return $this->handleValidationException($e, $this->associatedJobService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }

    public function printConfirmationList(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get the associated jobs
            $pdfConfirmationList = $this->associatedJobService->printConfirmationList($request);
            return $pdfConfirmationList;
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
