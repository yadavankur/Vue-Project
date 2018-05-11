<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Entities\ResourceType;
use App\Models\Repositories\BaseRepository;
use App\Models\Services\MenuService;
use App\Models\Services\TabService;
use App\Models\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
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
    public function authenticate(Request $request)
    {
        $rules = [  'email'     =>  'required|email' ,'password'  =>  'required' ];
        $this->validate($request, $rules);
        $credentials = $request->only('email', 'password');
        $credentials['active']  = 1;
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid Login Credential'], 401);
            }
            $user = Auth::user();
            $tabs = $this->tabService->getAll($user);
            $aclTabs = $this->userService->getAclResourceByType($user, ResourceType::TAB);
            $aclTabs = array_replace($tabs, $aclTabs);

            $menus = $this->menuService->getAll($user);
            $aclMenus = $this->userService->getAclResourceByType($user, ResourceType::MENU);
            $aclMenus = array_replace($menus, $aclMenus);
            $isAdmin = BaseRepository::isAdmin($user);
            return response()->json([
                'token' => $token,
                'aclTabs' => $aclTabs,
                'aclMenus' => $aclMenus,
                'isAdmin' => $isAdmin? 1: 0,
            ]);

        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
    }
}