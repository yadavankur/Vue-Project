<?php

namespace App\Http\Controllers;

use App\Models\Entities\GroupResourcePermission;
use App\Models\Entities\Log;
use App\Models\Services\GroupResourcePermissionService;
use App\Models\Services\RoleService;
use App\Models\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;


class GroupResourcePermissionController extends Controller
{
    private $userService;
    private $roleService;
    private $grpService;

    public function __construct(GroupResourcePermissionService $grpService,
                                UserService $userService,
                                RoleService $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
        $this->grpService = $grpService;

    }
    /**
     * @param Request $request
     * @return array|null
     */
    public function getGrpNodes(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            $grpNodes = $this->grpService->getByPaginate($request);
            return response()->json($grpNodes);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updateGrp(Request $request)
    {
        $rules = [
            'id' => 'required',
            'name'  =>  'required',
            'role' => 'required'
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $grp = $this->grpService->update($request);
            $this->grpService->LogEntity($grp, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('grp'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->grpService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function addGrp(Request $request)
    {
        $rules = [
            'name'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $grp = $this->grpService->add($request);
            $this->grpService->LogEntity($grp, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('grp'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->grpService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function deleteGrp(Request $request)
    {
        $rules = [
            'id'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $grp = $this->grpService->delete($request);
            $this->grpService->LogEntity($grp, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('grp'));
        }
        catch (Exception $e)
        {
            return $this->handleValidationException($e, $this->grpService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
}
