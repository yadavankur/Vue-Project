<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Repositories\BaseRepository;
use App\Models\Services\MenuService;
use App\Models\Services\PermissionService;
use App\Models\Services\TabService;
use App\Models\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class PermissionController extends Controller
{
    protected $permissionService;
    protected $userService;
    protected $tabService;
    protected $menuService;

    public function __construct(PermissionService $permissionService,
                                TabService $tabService,
                                MenuService $menuService,
                                UserService $userService)
    {
        $this->tabService = $tabService;
        $this->permissionService = $permissionService;
        $this->userService = $userService;
        $this->menuService = $menuService;
    }

    /**
     * @param Request $request
     * @return array|null
     */
    public function getPermissionNodes(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get all permissions
            $permissionNodes= $this->permissionService->getByPaginate($request);
            return response()->json($permissionNodes);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updatePermission(Request $request)
    {
        $rules = [
            'id' => 'required',
            'name'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $permission = $this->permissionService->update($request);
            $this->permissionService->LogEntity($permission, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('permission'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->permissionService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function addPermission(Request $request)
    {
        $rules = [
            'name'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $permission = $this->permissionService->add($request);
            $this->permissionService->LogEntity($permission, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('permission'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->permissionService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function deletePermission(Request $request)
    {
        $rules = [
            'id'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $permission = $this->permissionService->delete($request);
            $this->permissionService->LogEntity($permission, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('permission'));
        }
        catch (Exception $e)
        {
            return $this->handleValidationException($e, $this->permissionService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function getPermissionOptions(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $permissions = $this->permissionService->getPermissionOptions($request);
            return response()->json(compact('permissions'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function checkPermission(Request $request)
    {
        try {
//            $adminUserIds = [1, 2, 3];
            $user = JWTAuth::parseToken()->authenticate();
            $isAdmin = BaseRepository::isAdmin($user);
            if ($isAdmin)
            {
                $canAccess = true;
                return response()->json(compact('canAccess'));
            }
//            $roles = $user->roles();
//            foreach($roles as $role)
//            {
//                if (in_array($role->id, $adminUserIds))
//                {
//                    $canAccess = true;
//                    return response()->json(compact('canAccess'));
//                }
//            }
            $urlPath = $request->urlPath;
            // if either one in  tab or menu is allowed
            // then consider it as allowed
            $canAccess = $this->isAccessible($user, $urlPath, $this->tabService);
            if (!$canAccess)
            {
                $canAccess = $this->isAccessible($user, $urlPath, $this->menuService);
                if (!$canAccess)
                {
                    // no access
                    return response()->json(['error' => 'You have no access permission to this page.'], 444);
                }
            }
            return response()->json(compact('canAccess'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
