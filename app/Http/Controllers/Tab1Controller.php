<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Entities\ResourceType;
use App\Models\Services\Tab1Service;
use App\Models\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use DB;


class Tab1Controller extends Controller
{
    //
    protected $tab1Service;
    protected $userService;

    public function __construct(Tab1Service $tab1Service, UserService $userService)
    {
        $this->tab1Service = $tab1Service;
        $this->userService = $userService;
    }

    public function getTab1Nodes(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            //$user = JWTAuth::parseToken()->authenticate();
            // 2) get all tabs
            $tab1Nodes= $this->tab1Service->getByPaginate($request);
            return response()->json($tab1Nodes);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
