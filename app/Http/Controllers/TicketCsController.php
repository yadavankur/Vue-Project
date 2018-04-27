<?php

namespace App\Http\Controllers;



use App\Models\Entities\Log;
use App\Models\Services\StateService;
use App\Models\Services\TicketCsService;
use App\Models\Services\UserService;
use App\Models\Services\MenuService;
use App\Models\Services\LocationService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class TicketCsController extends Controller
{

   // private $urlPath = '/csticket';
    protected $userService;
    protected $menuService;
    protected $stateService;
    protected $locationService;
    protected $ticketcsService;

    public function __construct(  TicketCsService $ticketcsService,StateService $stateService,  MenuService $menuService, LocationService $locationService, UserService $userService)
    { $this->ticketcsService = $ticketcsService;
        $this->userService = $userService;
        $this->menuService = $menuService;
        $this->stateService = $stateService;
        $this->locationService = $locationService;
    }
    //

    public function addcsticket(Request $request)
    {   $rules = [ 'ticket_no'  =>  'required','QUOTE_ID'  =>  'required',
                   'ticket_type_id'  =>  'required' ,'status_id'  =>  'required','user1'  =>  'required' ];
        try {    $user = JWTAuth::parseToken()->authenticate();
                 $this->validate($request, $rules);
                 $gett1 = $this->ticketcsService->addcsticket($request);
                 //$this->ticketcsService->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
                 return response()->json(compact('gett1'));
               //  return response()->json($gett1);
            } catch (Exception $e) 
            {    return $this->handleValidationException($e, $this->ticketcsService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
            }
    }//----------------------------
    public function editcsticket(Request $request)
    {   $rules = [ 'ticket_no'  =>  'required',  ];
        try {    $user = JWTAuth::parseToken()->authenticate();
                 $this->validate($request, $rules);
                 $gett1 = $this->ticketcsService->editcsticket($request);
                 //$this->ticketcsService->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
                 return response()->json(compact('gett1'));
               //  return response()->json($gett1);
            } catch (Exception $e) 
            {    return $this->handleValidationException($e, $this->ticketcsService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
            }
    }
    //----------------
    public function displaycs(Request $request)
    //public function displaycs()
    {    try {   $user = JWTAuth::parseToken()->authenticate(); // 1) first get user from token to check validation
               // $this->validate($request, $rules);         
                $gett1= $this->ticketcsService->displaycs($request);
               // $gett1= $this->ticketcsService->displaycs();// 2) get all states
                 return response()->json(['gett1' => $gett1, ]);
                // return response()->json($gett1);
             } catch (Exception $e) 
             { return response()->json(['error' => $e->getMessage()], 500); }
    }

    public function getlastcsticket(Request $request)
    //public function displaycs()
    {    try {   $user = JWTAuth::parseToken()->authenticate(); // 1) first get user from token to check validation
               // $this->validate($request, $rules);         
                $gett2= $this->ticketcsService->getlastcsticket($request);

               // $gett1= $this->ticketcsService->displaycs();// 2) get all states
              // if($gett2){  
                   return response()->json(['gett2' => $gett2, ]);
                           // return $gett2;
                        //  }
                // else{ }
                // return response()->json($gett1);
             } catch (Exception $e) 
             { return response()->json(['error' => $e->getMessage()], 500); }
    }

    //----------------------pagination-------
    public function getByPaginate(Request $request)
    {
        try {
            // 1) first get user from token to check validation
             $user = JWTAuth::parseToken()->authenticate();
            // 2) get all permissions
             $tt= $this->ticketcsService->getByPaginate($request);
            // $tt= $this->ticketcsService->getlastcsticket($request);
            
            return response()->json($tt);
        } catch (Exception $e) { return response()->json(['error' => $e->getMessage()], 500);  }
    }
    //-----------------------------------------
    //-----------------delete
    public function deleteCsTicket(Request $request)
    {
        $rules = [ 'id'  =>  'required',   ];
        try {  $user = JWTAuth::parseToken()->authenticate();    $this->validate($request, $rules);
               $gett1 = $this->ticketcsService->deleteCsTicket($request);
               $this->ticketcsService->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
               return response()->json(compact('gett1'));
            }
        catch (Exception $e)
        { return $this->handleValidationException($e, $this->ticketcsService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
//---------------------------------addfile----
public function addfile(Request $request)
{  // $rules = [ 'ticket_no'  =>  'required',  ];
    try {    $user = JWTAuth::parseToken()->authenticate();
            // $this->validate($request, $rules);
             $gett1 = $this->ticketcsService->addfile($request);
             //$this->ticketcsService->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
          //   return response()->json(compact('gett1'));
            // return response()->json($gett1);
             return response()->json(compact('gett1'));
            // return response()->json($request->all());
        } catch (Exception $e) 
        {    return $this->handleValidationException($e, $this->ticketcsService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
}
//----------------------------------------------------
    public function gettype1ticket(Request $request)
        //public function displaycs()
        {    try {   $user = JWTAuth::parseToken()->authenticate(); // 1) first get user from token to check validation
                // $this->validate($request, $rules);         
                    $gett1= $this->ticketcsService->gettype1ticket($request);
                // $gett1= $this->ticketcsService->displaycs();// 2) get all states
                    // return response()->json(['gett1' => $gett1, ]);
                    return response()->json($gett1);
                    // return response()->json($gett1);
                //  return $gett1;
                } catch (Exception $e) 
                { return response()->json(['error' => $e->getMessage()], 500); }
        }
        public function gettype3ticket(Request $request)
        {    try {   $user = JWTAuth::parseToken()->authenticate(); 
                    $gett1= $this->ticketcsService->gettype3ticket($request);
                    return response()->json($gett1);
                } catch (Exception $e) 
                { return response()->json(['error' => $e->getMessage()], 500); }
        }

    
}
