<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Services\StateService;
use App\Models\Services\Testb1Service;
use App\Models\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class Testb1Controller extends Controller
{
    //
    protected $stateService; protected $userService; protected $testb1Service;
    public function __construct(StateService $stateService, UserService $userService, Testb1Service $testb1Service)
    {   $this->stateService = $stateService; 
        $this->testb1Service = $testb1Service;  
        $this->userService = $userService;  
    }

    public function getStateNodesnew2(Request $request)//------not sued anymore--but tested
    {    try {   $user = JWTAuth::parseToken()->authenticate(); // 1) first get user from token to check validation
                 $stateNodes= $this->stateService->getAllStateNodes();// 2) get all states
                 return response()->json(['stateNodes' => $stateNodes, ]);
             } catch (Exception $e) { return response()->json(['error' => $e->getMessage()], 500); }
    }

    public function getTest2(Request $request)
    {    try {   $user = JWTAuth::parseToken()->authenticate(); // 1) first get user from token to check validation
                 $gett1= $this->testb1Service->getTest1();// 2) get all states
                 return response()->json(['gett1' => $gett1, ]);
             } catch (Exception $e) { return response()->json(['error' => $e->getMessage()], 500); }
    }
//------------------add
    public function addTest(Request $request)
    {   $rules = [ 'name'  =>  'required',  ];
        try {    $user = JWTAuth::parseToken()->authenticate();
                 $this->validate($request, $rules);
                 $gett1 = $this->testb1Service->add($request);
                 $this->testb1Service->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
                 return response()->json(compact('gett1'));
            } catch (Exception $e) 
            {    return $this->handleValidationException($e, $this->testb1Service, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
            }
    }

    //-----------------delete
    public function deleteTest(Request $request)
    {
        $rules = [ 'id'  =>  'required',   ];
        try {  $user = JWTAuth::parseToken()->authenticate();    $this->validate($request, $rules);
               $gett1 = $this->testb1Service->delete($request);
              // $this->testb1Service->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
               return response()->json(compact('gett1'));
            }
        catch (Exception $e)
        { return $this->handleValidationException($e, $this->testb1Service, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    //---------------update
    public function updateTest(Request $request)
    {   $rules = ['id' => 'required', 'name'  =>  'required', ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $gett1 = $this->testb1Service->update1($request);
            $this->testb1Service->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('gett1'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->testb1Service, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }


}
