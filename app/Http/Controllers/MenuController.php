<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Entities\ResourceType;
use App\Models\Entities\User;
use App\Models\Services\MenuService;
use App\Models\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class MenuController extends Controller
{
    protected $menuService;
    protected $userService;

    public function __construct(MenuService $menuService, UserService $userService)
    {
        $this->menuService = $menuService;
        $this->userService = $userService;
    }

    public function show(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            //$user = User::find(6);
            // 2) get all menus
            $menus = $this->menuService->getAll($user);
            // 3) get accessible menus based on user
            $aclMenus = $this->userService->getAclResourceByType($user, ResourceType::MENU);
            // 3) rebuild menus
            $mergedMenus = array_replace($menus, $aclMenus);
            // 4) build tree menus
            $treeMenus = $this->menuService->getTreeMenus($mergedMenus, 'parent_id', 'id');
            return response()->json([
                'menus' => $treeMenus,
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    /**
     * @param Request $request
     * @return array|null
     */
    public function getMenuNodes(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get all menus
            $menuNodes= $this->menuService->getAllMenuNodes();
            return response()->json([
                'menuNodes' => $menuNodes,
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getPagination(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get all menu nodes
            $menuNodes= $this->menuService->getByPaginate($request);
            return response()->json($menuNodes);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updateMenu(Request $request)
    {
        $rules = [
            'id' => 'required',
            'name'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $menu = $this->menuService->update($request);
            $this->menuService->LogEntity($menu, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('menu'));
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function addMenu(Request $request)
    {
        $rules = [
            'name'  =>  'required',
            'parent_id' => 'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $menu = $this->menuService->add($request);
            $this->menuService->LogEntity($menu, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('menu'));
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function deleteMenu(Request $request)
    {
        $rules = [
            'id'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $menu = $this->menuService->delete($request);
            $this->menuService->LogEntity($menu, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('menu'));
        }
        catch (Exception $e)
        {
            return $this->handleValidationException($e, $this->menuService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
}
