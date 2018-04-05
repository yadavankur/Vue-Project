<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Entities\ResourceType;
use App\Models\Services\GroupService;
use App\Models\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class GroupController extends Controller
{
    protected $groupService;
    protected $userService;

    public function __construct(GroupService $groupService, UserService $userService)
    {
        $this->groupService = $groupService;
        $this->userService = $userService;
    }

//    public function show(Request $request)
//    {
//        // 1) first get user from token to check validation
//        $user = JWTAuth::parseToken()->authenticate();
//
//        // 2) get all groups
//        $groups = $this->groupService->getAll();
//
//        // 2) get accessible groups based on user
//        $aclGroups = $this->userService->getAclResourceByType($user, ResourceType::LOCATION);
//
//        // 3) rebuild groups
//        $mergedGroups = array_replace($groups, $aclGroups);
//
//        // 4) return groups
//        return response()->json([
//            'groups' => $mergedGroups,
//        ]);
//    }
    /**
     * @param Request $request
     * @return array|null
     */
    public function getGroupNodes(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get all groups
            $groupNodes= $this->groupService->getByPaginate($request);
            return response()->json($groupNodes);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function usersaspergroups(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get all groups
            $groupNodes= $this->groupService->usersaspergroups($request);
            return response()->json(compact('groupNodes'));
           // return response()->json($groupNodes);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updateGroup(Request $request)
    {
        $rules = [
            'id' => 'required',
            'name'  =>  'required',
            'role.name'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $group = $this->groupService->update($request);
            $this->groupService->LogEntity($group, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('group'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->groupService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function addGroup(Request $request)
    {
        $rules = [
            'name'  =>  'required',
            'role.name' => 'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $group = $this->groupService->add($request);
            $this->groupService->LogEntity($group, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('group'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->groupService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function deleteGroup(Request $request)
    {
        $rules = [
            'id'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $group = $this->groupService->delete($request);
            $this->groupService->LogEntity($group, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('group'));
        }
        catch (Exception $e)
        {
            return $this->handleValidationException($e, $this->groupService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
}
