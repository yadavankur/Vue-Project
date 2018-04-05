<?php

namespace App\Http\Controllers;

use App\Models\Entities\CPM_OrderMatrix;
use App\Models\Entities\Location;
use App\Models\Entities\Log;
use App\Models\Entities\ResourceType;
use App\Models\Entities\User;
use App\Models\Services\Cpm2Service;
use App\Models\Services\LocationService;
use App\Models\Services\MenuService;
use App\Models\Services\StateService;
use App\Models\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class Cpm2Controller extends Controller
{
    private $urlPath = '/cpm2';
    protected $cpm2Service;
    protected $userService;
    protected $menuService;
    protected $stateService;
    protected $locationService;

    public function __construct(Cpm2Service $cpm2Service,  StateService $stateService,  MenuService $menuService, LocationService $locationService, UserService $userService)
    {   $this->cpm2Service = $cpm2Service;
        $this->userService = $userService;
        $this->menuService = $menuService;
        $this->stateService = $stateService;
        $this->locationService = $locationService;
    }

    public function getCpm2ServiceNodes(Request $request) //for showing all the records
    {   try {   $user = JWTAuth::parseToken()->authenticate();
               $cpm2ServiceNodes= $this->cpm2Service->getByPaginate($request);
               return response()->json($cpm2ServiceNodes);
            } catch (Exception $e) {   return response()->json(['error' => $e->getMessage()], 500);  }
    }
//-------------------------till above code---its for display records
    public function deleteCpm2Service(Request $request)
    {   $rules = ['id'  =>  'required',  ];
        try {    $user = JWTAuth::parseToken()->authenticate();
                 $this->validate($request, $rules);
                 $cpm2Service = $this->cpm2Service->delete($request);
                 $this->cpm2Service->LogEntity($cpm2Service, 'success', __CLASS__ . '::' .__FUNCTION__);
                 return response()->json(compact('cpm2Service'));
            }
        catch (Exception $e)
            {   return $this->handleValidationException($e, $this->cpm2Service, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
            }
    }
//-----------above fn is for delete record---below is for add------------
public function addCpm2Service(Request $request)
    {
        $rules = [ 'name'  =>'required', 'state.id' => 'required', 'state.name' => 'required','location.id' => 'required','location.name' => 'required' ];
        try 
        {   $user = JWTAuth::parseToken()->authenticate();  $this->validate($request, $rules);
            $cpm2Service = $this->cpm2Service->add($request); //cpmServiceService--replace it with cpm2service(file name of service)
            $this->cpm2Service->LogEntity($cpm2Service, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('cpm2Service'));//response
        } catch (Exception $e) { return $this->handleValidationException($e, $this->cpm2Service, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);}
    
    }

    public function updateCpm2Service(Request $request)
    {
        $rules = ['id' => 'required','name'  =>  'required','state.id' => 'required','state.name' => 'required','location.id' => 'required',
            'location.name' => 'required'
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $cpm2Service = $this->cpm2Service->update($request);
            $this->cpm2Service->LogEntity($cpm2Service, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('cpm2Service'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->cpm2Service, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
//--------------------------
} //-------end class