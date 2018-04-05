<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Services\MenuService;
use App\Models\Services\RoleService;
use App\Models\Services\UserService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class RoleController extends Controller
{
    private $urlPath = '/roles';
    protected $roleService;
    protected $userService;
    protected $menuService;
    public function __construct(RoleService $roleService,
                                MenuService $menuService,
                                UserService $userService)
    {
        $this->roleService = $roleService;
        $this->userService = $userService;
        $this->menuService = $menuService;
    }

    public function lists(Request $request)
    {
        // 1) first get user from token to check validation
        $user = JWTAuth::parseToken()->authenticate();
//        $canAccess = $this->isAccessible($user, $this->urlPath, $this->menuService);
//        if (!$canAccess)
//        {
//            // no access
//            return response()->json(['error' => 'You have no access permission to this page.'], 444);
//        }
         // 2) get current roles
        $roles = $this->userService->getRoles($user);

        // // 3) check whether the roles have children
        // $rawSql = "
        //         WITH ret AS(
        //           SELECT  *
        //           FROM    roles
        //           WHERE   id = ?
        //           UNION ALL
        //           SELECT  t.*
        //           FROM  roles t INNER JOIN
        //                 ret r ON t.parent_id = r.id
        //         )
        //         SELECT  *
        //         FROM    ret order by id asc
        // ";

        // $roleRets = [];
        // foreach($roles as $role)
        // {
        //     if ($role->canEdit) {
        //         $rets = DB::select($rawSql, array($role->id));
        //         $rets = json_decode(json_encode($rets), true);
        //         if (count($rets) > 0) {
        //             $treeRoles = Role::getTreeRoles($rets, 'parent_id', 'id');
        //             $roleRets[$role->id] = $treeRoles;
        //         }
        //     }
        // }
        //2) get all components belongs to this page
        // $rolesAll= Role::where('active', 1)->get()->keyBy('id')->toArray();
        // $retRoles = [];
        $treeRoles = $this->roleService->getChildRoles($user);

        // 4) return processes
        return response()->json([
            'assingedRoles' => json_encode($roles),
            'childRoles' => json_encode($treeRoles),
        ]);

    }

    public function updateRole(Request $request)
    {
        $rules = [
            'id' => 'required',
            'name'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $role = $this->roleService->update($request);
            $this->roleService->LogEntity($role, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('role'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->roleService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function addRole(Request $request)
    {
        $rules = [
            'name'  =>  'required',
            'parent_id' => 'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $role = $this->roleService->add($request);
            $this->roleService->LogEntity($role, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('role'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->roleService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function deleteRole(Request $request)
    {
        $rules = [
            'id'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $role = $this->roleService->delete($request);
            $this->roleService->LogEntity($role, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('role'));
        }
        catch (Exception $e)
        {
            return $this->handleValidationException($e, $this->roleService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    /**
     * @param Request $request
     * @return array|null
     */
    public function getRoleNodes(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get all pages
            $roleNodes= $this->roleService->getByPaginate($request);
            return response()->json($roleNodes);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getAllRoleOptions(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $roles = $this->roleService->getAllRoleOptions($request);
            // $roles = $this->roleService->getChildRoles($user);
            return response()->json(compact('roles'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
