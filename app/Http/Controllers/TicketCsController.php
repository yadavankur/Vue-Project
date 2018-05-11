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
                   return response()->json(compact('gett1'));
            } catch (Exception $e) 
            {    return $this->handleValidationException($e, $this->ticketcsService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
            }
    }//----------------------------
    public function editcsticket(Request $request)
    {   $rules = [ 'ticket_no'  =>  'required',  ];
        try {    $user = JWTAuth::parseToken()->authenticate();
                 $this->validate($request, $rules);
                 $gett1 = $this->ticketcsService->editcsticket($request);
                 return response()->json(compact('gett1'));
              } catch (Exception $e) 
            {    return $this->handleValidationException($e, $this->ticketcsService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
            }
    }
    //----------------
    public function displaycs(Request $request)
    {    try {   $user = JWTAuth::parseToken()->authenticate(); 
                $gett1= $this->ticketcsService->displaycs($request);
                return response()->json(['gett1' => $gett1, ]);
             } catch (Exception $e) 
             { return response()->json(['error' => $e->getMessage()], 500); }
    }

    public function getlastcsticket(Request $request)
     {    try {   $user = JWTAuth::parseToken()->authenticate(); 
                $gett2= $this->ticketcsService->getlastcsticket($request);
               return response()->json(['gett2' => $gett2, ]);
             } catch (Exception $e) 
             { return response()->json(['error' => $e->getMessage()], 500); }
    }


    public function getByPaginate(Request $request)
    {       try {   $user = JWTAuth::parseToken()->authenticate();
             $tt= $this->ticketcsService->getByPaginate($request);
              return response()->json($tt);
        } catch (Exception $e) { return response()->json(['error' => $e->getMessage()], 500);  }
    }
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
             $gett1 = $this->ticketcsService->addfile($request);
             return response()->json(compact('gett1'));
        } catch (Exception $e) 
        {    return $this->handleValidationException($e, $this->ticketcsService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
}
//----------------------------------------------------
    public function gettype1ticket(Request $request)
        {    try {   $user = JWTAuth::parseToken()->authenticate(); 
                     $gett1= $this->ticketcsService->gettype1ticket($request);
                    return response()->json($gett1);
                } catch (Exception $e) 
                { return response()->json(['error' => $e->getMessage()], 500); }
        }
        public function gettype2Aticket(Request $request)
        {    try {   $user = JWTAuth::parseToken()->authenticate(); 
                    $gett1= $this->ticketcsService->gettype2Aticket($request);
                    return response()->json($gett1);
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
        public function gettype4ticket(Request $request)
        {    try {   $user = JWTAuth::parseToken()->authenticate(); 
                    $gett1= $this->ticketcsService->gettype4ticket($request);
                    return response()->json($gett1);
                } catch (Exception $e) 
                { return response()->json(['error' => $e->getMessage()], 500); }
        }
        public function gettype5ticket(Request $request)
        {    try {   $user = JWTAuth::parseToken()->authenticate(); 
                    $gett1= $this->ticketcsService->gettype5ticket($request);
                    return response()->json($gett1);
                } catch (Exception $e) 
                { return response()->json(['error' => $e->getMessage()], 500); }
        }

    
}
