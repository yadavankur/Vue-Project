<?php
namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Entities\ResourceType;
use App\Models\Services\PageService;
use App\Models\Services\ProcessService;
use App\Models\Services\ComponentService;
use App\Models\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProcessController extends Controller
{
    protected $processService;
    protected $userService;
    protected $componentService;
    protected $pageService;

    public function __construct(ProcessService $processService,
                                UserService $userService,
                                PageService $pageService,
                                ComponentService $componentService)
    {
        $this->processService = $processService;
        $this->userService = $userService;
        $this->componentService = $componentService;
        $this->pageService = $pageService;
    }

    public function show(Request $request)
    {
        // 1) first get user from token to check validation
        $user = JWTAuth::parseToken()->authenticate();
        $rules = [
            'componentName' => 'required'
        ];
        $this->validate($request, $rules);
        $componentName = $request->input('componentName');
        $component = $this->componentService->getComponentByName($componentName);
        if (!$component)
            return response()->json([
                'processes' => [],
            ]);
        // 2) get all processes belongs to this component
        $processes = $this->processService->getAll($component->id, $user);
        // 2) get accessible processes based on user
        $aclProcesses = $this->userService->getAclResourceByType($user, ResourceType::PROCESS);
        // 3) rebuild $mergedProcesses
        // replace the first array with keys on in first array
        $mergedProcesses = array_replace($processes, array_intersect_key($aclProcesses, $processes));

        // 4) rebuild processes with name key
        $namedProcesses = [];
        foreach($mergedProcesses as $process)
        {
            if (isset($namedProcesses[$process['name']]))
            {
                //error
                return response()->json([
                    'error' => 'Duplicated process name found in this page!',
                ]);
            }
            else
                $namedProcesses[$process['name']] = $process;
        }
        // 5) return processes
        return response()->json([
            'processes' => json_encode($namedProcesses),
        ]);
    }
    /**
     * @param Request $request
     * @return array|null
     */
    public function getProcessNodes(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get all components
            $processNodes= $this->processService->getByPaginate($request);
            return response()->json($processNodes);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updateProcess(Request $request)
    {
        $rules = [
            'id' => 'required',
            'name'  =>  'required',
            'page.id'  =>  'required',
            'page.name'  =>  'required',
            'component.id'  =>  'required',
            'component.name'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $process = $this->processService->update($request);
            $this->processService->LogEntity($process, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('process'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->processService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function addProcess(Request $request)
    {
        $rules = [
            'name'  =>  'required',
            'page.id'  =>  'required',
            'page.name'  =>  'required',
            'component.id'  =>  'required',
            'component.name'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $process = $this->processService->add($request);
            $this->processService->LogEntity($process, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('process'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->processService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function deleteProcess(Request $request)
    {
        $rules = [
            'id'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $process = $this->processService->delete($request);
            $this->processService->LogEntity($process, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('process'));
        }
        catch (Exception $e)
        {
            return $this->handleValidationException($e, $this->processService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function getComponentOptions(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $components = $this->componentService->getAllComponentOptions($request);
            return response()->json(compact('components'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getCascadeComponentOptions(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $cascadeComponents = $this->pageService->getCascadeComponents();
            return response()->json(compact('cascadeComponents'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
