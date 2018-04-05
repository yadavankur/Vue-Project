<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Entities\ResourceType;
use App\Models\Services\PageService;
use App\Models\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class PageController extends Controller
{
    protected $pageService;
    protected $userService;

    public function __construct(PageService $pageService, UserService $userService)
    {
        $this->pageService = $pageService;
        $this->userService = $userService;
    }

    public function show(Request $request)
    {
        // 1) first get user from token to check validation
        $user = JWTAuth::parseToken()->authenticate();

        // 2) get all pages
        $pages = $this->pageService->getAll($user);

        // 2) get accessible pages based on user
        $aclPages = $this->userService->getAclResourceByType($user, ResourceType::PAGE);

        // 3) rebuild pages
        $mergedPages = array_replace($pages, $aclPages);

        // 4) return pages
        return response()->json([
            'pages' => $mergedPages,
        ]);
    }
    /**
     * @param Request $request
     * @return array|null
     */
    public function getPageNodes(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get all pages
            $pageNodes= $this->pageService->getByPaginate($request);
            return response()->json($pageNodes);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getPageOptions(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $pages = $this->pageService->getPageOptions();
            return response()->json(compact('pages'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updatePage(Request $request)
    {
        $rules = [
            'id' => 'required',
            'name'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $page = $this->pageService->update($request);
            $this->pageService->LogEntity($page, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('page'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->pageService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function addPage(Request $request)
    {
        $rules = [
            'name'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $page = $this->pageService->add($request);
            $this->pageService->LogEntity($page, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('page'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->pageService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function deletePage(Request $request)
    {
        $rules = [
            'id'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $page = $this->pageService->delete($request);
            $this->pageService->LogEntity($page, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('page'));
        }
        catch (Exception $e)
        {
            return $this->handleValidationException($e, $this->pageService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
}
