<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Entities\ResourceType;
use App\Models\Services\TabService;
use App\Models\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use DB;

class TabController extends Controller
{
    protected $tabService;
    protected $userService;

    public function __construct(TabService $tabService, UserService $userService)
    {
        $this->tabService = $tabService;
        $this->userService = $userService;
    }

    public function show(Request $request)
    {
        // 1) first get user from token to check validation
        $user = JWTAuth::parseToken()->authenticate();

        // 2) get all tabs
        $tabs = $this->tabService->getAll($user);

        // 2) get accessible tabs based on user
        $aclTabs = $this->userService->getAclResourceByType($user, ResourceType::TAB);

        // 3) rebuild tabs
        $mergedTabs = array_replace($tabs, $aclTabs);

        // 4) return tabs
        return response()->json([
            'tabs' => $mergedTabs,
        ]);
    }
    /**
     * @param Request $request
     * @return array|null
     */
    public function getTabNodes(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            //$user = JWTAuth::parseToken()->authenticate();
            // 2) get all tabs
            $tabNodes= $this->tabService->getByPaginate($request);
            return response()->json($tabNodes);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updateTab(Request $request)
    {
        $rules = [
            'id' => 'required',
            'name'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $tab = $this->tabService->update($request);
            $this->tabService->LogEntity($tab, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('tab'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->tabService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function addTab(Request $request)
    {
        $rules = [
            'name'  =>  'required',
            'link' => 'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $tab = $this->tabService->add($request);
            $this->tabService->LogEntity($tab, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('tab'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->tabService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function deleteTab(Request $request)
    {
        $rules = [
            'id'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $tab = $this->tabService->delete($request);
            $this->tabService->LogEntity($tab, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('tab'));
        }
        catch (Exception $e)
        {
            return $this->handleValidationException($e, $this->tabService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
}
