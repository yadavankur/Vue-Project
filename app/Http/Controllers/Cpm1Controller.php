<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Entities\User;
use App\Models\Services\Cpm1Service;
use App\Models\Services\MenuService;
use App\Models\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class Cpm1Controller extends Controller
{

    private $urlPath = '/cpm1';
    protected $cpm1Service;
    protected $userService;
    protected $menuService;

    public function __construct(Cpm1Service $cpm1Service,
                                MenuService $menuService,
                                UserService $userService)
    {
        $this->cpm1Service = $cpm1Service;
        $this->userService = $userService;
        $this->menuService = $menuService;
    }



    public function getCpm1ActivityNodes(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get all cpmActivitys
            $cpm1ActivityNodes= $this->cpm1Service->getByPaginate($request, $this->userService);
            return response()->json($cpm1ActivityNodes);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    //---------------display finish-------delete start-------
    public function deleteCpm1Activity(Request $request)
    {
        $rules = [ 'id'  =>  'required',   ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $cpm1Activity = $this->cpm1Service->delete($request);
            $this->cpm1Service->LogEntity($cpm1Activity, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('cpm1Activity'));
        }
        catch (Exception $e)
        {
            return $this->handleValidationException($e, $this->cpm1Service, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
}
