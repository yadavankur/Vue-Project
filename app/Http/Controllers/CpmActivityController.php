<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Entities\User;
use App\Models\Services\CpmActivityService;
use App\Models\Services\MenuService;
use App\Models\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CpmActivityController extends Controller
{
    private $urlPath = '/cpmactivities';
    protected $cpmActivityService;
    protected $userService;
    protected $menuService;

    public function __construct(CpmActivityService $cpmActivityService,
                                MenuService $menuService,
                                UserService $userService)
    {
        $this->cpmActivityService = $cpmActivityService;
        $this->userService = $userService;
        $this->menuService = $menuService;
    }

    /**
     * @param Request $request
     * @return array|null
     */
    public function getCpmActivityNodes(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get all cpmActivitys
            $cpmActivityNodes= $this->cpmActivityService->getByPaginate($request, $this->userService);
            return response()->json($cpmActivityNodes);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updateCpmActivity(Request $request)
    {
        $rules = [
            'id' => 'required',
            'name'  =>  'required',
            'duration' =>  'required',
            'isFull' => 'required',
            'service.id' => 'required',
            'service.name' => 'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $cpmActivity = $this->cpmActivityService->update($request);
            $this->cpmActivityService->LogEntity($cpmActivity, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('cpmActivity'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->cpmActivityService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function addCpmActivity(Request $request)
    {
        $rules = [
            'service.id' => 'required',
            'service.name' => 'required',
            'name'  =>  'required',
            'duration' =>  'required',
            'isFull' => 'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $cpmActivity = $this->cpmActivityService->add($request);
            $this->cpmActivityService->LogEntity($cpmActivity, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('cpmActivity'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->cpmActivityService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function deleteCpmActivity(Request $request)
    {
        $rules = [
            'id'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $cpmActivity = $this->cpmActivityService->delete($request);
            $this->cpmActivityService->LogEntity($cpmActivity, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('cpmActivity'));
        }
        catch (Exception $e)
        {
            return $this->handleValidationException($e, $this->cpmActivityService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function getCpmActivityOptions(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $activities = $this->cpmActivityService->getCpmActivityOptions($request);
            return response()->json(compact('activities'));
        }
        catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getAssociatedUsers(Request $request)
    {
        // normally, the location admin will
        // do the user assignment to specific activity
        // thus getting its all child users is enough
        // 1) get role based on user id
        // 2) get all users belong to this role
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $users = $this->cpmActivityService->getAssociatedUsers($request);
            return response()->json(compact('users'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getAssociatedManagers(Request $request)
    {
        // normally, the location admin will
        // do the user assignment to specific activity
        // thus getting its all child users is enough
        // 1) get role based on user id
        // 2) get all users belong to this role
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $managers = $this->cpmActivityService->getAssociatedManagers($request);
            return response()->json(compact('managers'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getAssociatedUserOptions(Request $request)
    {
        // normally, the location admin will
        // do the user assignment to specific activity
        // thus getting its all child users is enough
        // 1) get role based on user id
        // 2) get all users belong to this role
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $users = $this->cpmActivityService->getAssociatedUserOptions($request);
            return response()->json(compact('users'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getAssociatedGroupOptions(Request $request)
    {
        // normally, the location admin will
        // do the user assignment to specific activity
        // thus getting its all child users is enough
        // 1) get role based on user id
        // 2) get all users belong to this role
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $groups = $this->cpmActivityService->getAssociatedGroupOptions($request);
            return response()->json(compact('groups'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updateAssociatedUsers(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $cpmActivityUsers = $this->cpmActivityService->updateAssociatedUser($request);
            $this->cpmActivityService->LogEntity($cpmActivityUsers, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('cpmActivityUsers'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->cpmActivityService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
}
