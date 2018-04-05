<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Entities\ResourceType;
use App\Models\Repositories\BaseRepository;
use App\Models\Services\MenuService;
use App\Models\Services\TabService;
use App\Models\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;


class UserController extends Controller
{
    private $userService;
    private $menuService;
    private $tabService;

    public function __construct(UserService $userService,
                                MenuService $menuService,
                                TabService $tabService)
    {
        $this->menuService = $menuService;
        $this->tabService = $tabService;
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $data = [];
        $data['name'] = $request->user()->name;
        $data['email'] = $request->user()->email;
        return response()->json([
            'data' => $data,
        ]);
    }
    public function show(Request $request)
    {
        // $user = $request->user();
        $user = JWTAuth::parseToken()->authenticate();
        $isAdmin = BaseRepository::isAdmin($user);
        // get accessible router link list
        //$aclTabs = $this->userService->getAclResourceByType($user, ResourceType::TAB);
        //$aclMenus = $this->userService->getAclResourceByType($user, ResourceType::MENU);
        $tabs = $this->tabService->getAll($user);
        $aclTabs = $this->userService->getAclResourceByType($user, ResourceType::TAB);
        $aclTabs = array_replace($tabs, $aclTabs);

        $menus = $this->menuService->getAll($user);
        $aclMenus = $this->userService->getAclResourceByType($user, ResourceType::MENU);
        $aclMenus = array_replace($menus, $aclMenus);

        $roles = $user->roles();
        $roleNames = [];
        foreach($roles as $role)
        {
            $roleNames[] = $role->name;
        }
        $roleName = implode(', ', $roleNames);
        $user['role'] = $roleName;

        $user['aclTabs'] = $aclTabs;
        $user['aclMenus'] = $aclMenus;
        $user['isAdmin'] = $isAdmin? 1: 0;
        return $user;
//        return response()->json([
//            'user' => json_encode($user)
//        ]);
    }
    public function lists(Request $request)
    {
        return $this->user->all();
    }

    public function getusers(Request $request)
    {    try {   $user = JWTAuth::parseToken()->authenticate(); // 1) first get user from token to check validation
                 $gett1= $this->userService->getAllUserNodes();// 2) get all states

                return $gett1;
                 //return response()->json(['gett1' => $gett1, ]);
             } catch (Exception $e) { return response()->json(['error' => $e->getMessage()], 500); }
    }
    /**
     * @param Request $request
     * @return array|null
     */
    public function getUserNodes(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            $roles = $user->roles();
            $canEdit = false;
            foreach($roles as $role)
            {
                if ($role->can_edit)
                {
                    $canEdit = true;
                    break;
                }
            }
            if (!$canEdit)
            {
                // no access
                return response()->json(['error' => 'You have no access permission to this page.'], 444);
            }
            $userNodes = $this->userService->getByPaginate($request);
            return response()->json($userNodes);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updateUser(Request $request)
    {
        $rules = [
            'id' => 'required',
            'name'  =>  'required',
            'email' => 'required|email',
//            'role.id' => 'required',
//            'role.name' => 'required',
//            'group.id' => 'required',
//            'group.name' => 'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $user = $this->userService->update($request);
            $this->userService->LogEntity($user, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('user'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->userService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function addUser(Request $request)
    {
        $rules = [
            'name'  =>  'required',
            'email' => 'required|email',
//            'role.id' => 'required',
//            'role.name' => 'required',
//            'group.id' => 'required',
//            'group.name' => 'required',
            'password' => 'required',
            'confirmPassword' => 'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $user = $this->userService->add($request);
            $this->userService->LogEntity($user, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('user'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->userService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function deleteUser(Request $request)
    {
        $rules = [
            'id'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $user = $this->userService->delete($request);
            $this->userService->LogEntity($user, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('user'));
        }
        catch (Exception $e)
        {
            return $this->handleValidationException($e, $this->userService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function getRoleOptions(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $roles = $this->userService->getRoleOptions($request);
            return response()->json(compact('roles'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getGroupOptions(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $groups = $this->userService->getGroupOptions($request);
            return response()->json(compact('groups'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCascadeRolesGroups(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $cascadeRolesGroups = $this->userService->getCascadeRolesGroups($request);
            return response()->json(compact('cascadeRolesGroups'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
