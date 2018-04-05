<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Entities\ResourceType;
use App\Models\Entities\User;
use App\Models\Services\ComponentService;
use App\Models\Services\PageService;
use App\Models\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ComponentController extends Controller
{
    protected $componentService;
    protected $userService;
    protected $pageService;

    public function __construct(ComponentService $componentService,
                                UserService $userService,
                                PageService $pageService)
    {
        $this->componentService = $componentService;
        $this->userService = $userService;
        $this->pageService = $pageService;
    }

    public function show(Request $request)
    {

        // 1) first get user from token to check validation
        $user = JWTAuth::parseToken()->authenticate();
        $rules = [
            'pageName' => 'required'
        ];
        $this->validate($request, $rules);
        $pageName = $request->input('pageName');
        $page = $this->pageService->getPageByName($pageName);
        if (!$page)
            return response()->json(['components' => [], ]);
        // 2) get all components belongs to this page
        $components = $this->componentService->getAll($page->id, $user);
        // 2) get accessible components based on user
        $aclComponents = $this->userService->getAclResourceByType($user, ResourceType::COMPONENT);
        // 3) rebuild $mergedComponents
        // replace the first array with keys on in first array
        // $mergedComponents = array_replace($components, $aclComponents);
        $intersectComponents = array_intersect_key($aclComponents, $components);
        $mergedComponents = array_replace($components, $intersectComponents);

        // 4) rebuild components with name key
        $namedComponents = [];
        foreach($mergedComponents as $component)
        {
            if (isset($namedComponents[$component['name']]))
            {  //error
                return response()->json([ 'error' => 'Duplicated component name found in this page!', ]);
            }
            else
                $namedComponents[$component['name']] = $component;
        }
        // 5) return components
        return response()->json(['components' => json_encode($namedComponents), ]);
    }
    /**
     * @param Request $request
     * @return array|null
     */
    public function getComponentNodes(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get all components
            $componentNodes= $this->componentService->getByPaginate($request);
            return response()->json($componentNodes);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updateComponent(Request $request)
    {
        $rules = [
            'id' => 'required',
            'name'  =>  'required',
            'page.value' => 'required',
            'page.label' => 'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $component = $this->componentService->update($request);
            $this->componentService->LogEntity($component, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('component'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->componentService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function addComponent(Request $request)
    {
        $rules = [
            'name'  =>  'required',
            'page.value' => 'required',
            'page.label' => 'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $component = $this->componentService->add($request);
            $this->componentService->LogEntity($component, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('component'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->componentService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function deleteComponent(Request $request)
    {
        $rules = [
            'id'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $component = $this->componentService->delete($request);
            $this->componentService->LogEntity($component, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('component'));
        }
        catch (Exception $e)
        {
            return $this->handleValidationException($e, $this->componentService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function getComponentOptions(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $components = $this->componentService->getComponentOptions($request);
            return response()->json(compact('components'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getAllComponentOptions(Request $request)
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
    public function getCascadeComponents(Request $request)
    {
        // get cascade components
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
