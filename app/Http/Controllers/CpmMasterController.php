<?php

namespace App\Http\Controllers;


use App\Models\Entities\Log;
use App\Models\Services\CpmMasterService;
use App\Models\Services\MenuService;
use App\Models\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CpmMasterController extends Controller
{
    private $urlPath = '/cpmmasters';
    protected $cpmMasterService;
    protected $userService;
    protected $menuService;

    public function __construct(CpmMasterService $cpmMasterService,
                                MenuService $menuService,
                                UserService $userService)
    {
        $this->cpmMasterService = $cpmMasterService;
        $this->userService = $userService;
        $this->menuService = $menuService;
    }

    /**
     * @param Request $request
     * @return array|null
     */
    public function getCpmMasterNodes(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get all cpmMasters
            $cpmMasterNodes= $this->cpmMasterService->getByPaginate($request, $this->userService);
            return response()->json($cpmMasterNodes);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updateCpmMaster(Request $request)
    {
        $rules = [
            'id' => 'required',
            'service.id'  =>  'required',
            'activity.id'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $cpmMaster = $this->cpmMasterService->update($request);
            $this->cpmMasterService->LogEntity($cpmMaster, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('cpmMaster'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->cpmMasterService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function addCpmMaster(Request $request)
    {
        $rules = [
            'service.id'  =>  'required',
            'activity.id'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $cpmMaster = $this->cpmMasterService->add($request);
            $this->cpmMasterService->LogEntity($cpmMaster, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('cpmMaster'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->cpmMasterService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function deleteCpmMaster(Request $request)
    {
        $rules = [
            'id'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $cpmMaster = $this->cpmMasterService->delete($request);
            $this->cpmMasterService->LogEntity($cpmMaster, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('cpmMaster'));
        }
        catch (Exception $e)
        {
            return $this->handleValidationException($e, $this->cpmMasterService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
}
