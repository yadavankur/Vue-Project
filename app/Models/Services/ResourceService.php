<?php

namespace App\Models\Services;

use App\Models\Entities\CMenu;
use App\Models\Repositories\CMenuRepository;
use App\Models\Repositories\ComponentRepository;
use App\Models\Repositories\LocationRepository;
use App\Models\Repositories\PageRepository;
use App\Models\Repositories\ProcessRepository;
use App\Models\Repositories\StateRepository;
use App\Models\Repositories\TabRepository;
use App\Models\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;

class ResourceService extends BaseService
{
    protected $userRepository;
    protected $stateRepository;
    protected $locationRepository;
    protected $cMenuRepository;
    protected $tabRepository;
    protected $pageRepository;
    protected $componentRepository;
    protected $processRepository;


    public function __construct(UserRepository $userRepository,
                                StateRepository $stateRepository,
                                LocationRepository $locationRepository,
                                CMenuRepository $cMenuRepository,
                                TabRepository $tabRepository,
                                PageRepository $pageRepository,
                                ComponentRepository $componentRepository,
                                ProcessRepository $processRepository)
    {
        $this->userRepository = $userRepository;
        $this->stateRepository = $stateRepository;
        $this->locationRepository = $locationRepository;
        $this->cMenuRepository = $cMenuRepository;
        $this->tabRepository = $tabRepository;
        $this->pageRepository = $pageRepository;
        $this->componentRepository = $componentRepository;
        $this->processRepository = $processRepository;
    }
    public function getResourceOptions(Request $request)
    {
        $resourceRepository = null;
        $selectedResourceTypeName = $request->selectedResourceType;
        $resourceClass = 'App\\Models\\Entities\\' . $selectedResourceTypeName;
        switch($selectedResourceTypeName)
        {
            case 'Location':
                $resourceOptions = $resourceClass::where('active',1)->with('state')->get();
                break;
            case 'Menu':
                $resourceOptions = $this->cMenuRepository->findAllBy('active',1);
                break;
            case 'Tab':
                $resourceOptions = $resourceClass::where('active',1)->with('page')->get();
                break;
            case 'Page':
                $resourceOptions = $this->pageRepository->findAllBy('active',1);
                break;
            case 'Component':
                $resourceOptions = $resourceClass::where('active',1)->with('page')->with('parent')->get();
                break;
            case 'Process':
                $resourceOptions = $resourceClass::where('active',1)->with('component')->get();
                break;
            default:
                throw new Exception('Invalid resource type!');
                break;
        }
        return $resourceOptions;
    }
    public function getResourceName(Request $request)
    {
        $selectedResourceTypeName = $request->resource_type;
        $selectedResourceId = $request->resource_id;
        $resourceClass = 'App\\Models\\Entities\\' . $selectedResourceTypeName;
        $resourceName = '';
        switch($selectedResourceTypeName)
        {
            case 'Location':
                $resource = $resourceClass::where('id', $selectedResourceId)
                            ->where('active',1)->with('state')->first();
                $resourceName = $resource->state ? $resource->state->name . '/' . $resource->name : $resource->name;
                break;
            case 'Menu':
                $resource = $resourceClass::where('id', $selectedResourceId)
                            ->where('active',1)->first();
                $resourceName = $resource->name;
                break;
            case 'Tab':
                $resource = $resourceClass::where('id', $selectedResourceId)
                            ->where('active',1)->with('page')->first();
                $resourceName = $resource->page? $resource->page->name . '/' . $resource->name : $resource->name;
                break;
            case 'Page':
                $resource = $resourceClass::where('id', $selectedResourceId)
                            ->where('active',1)->first();
                $resourceName = $resource->name;
                break;
            case 'Component':
                $resource = $resourceClass::where('id', $selectedResourceId)
                            ->where('active',1)->with('page')->with('parent')->first();
                $resourcePageName = $resource->page? $resource->page->name . '/' : '';
                $resourceParentName = $resource->parent? $resource->parent->name . '/' : '';
                $resourceName = $resourcePageName .  $resourceParentName . $resource->name;
                break;
            case 'Process':
                $resource = $resourceClass::where('id', $selectedResourceId)
                            ->where('active',1)->with('component')->first();
                $resourcePageName = $resource->component->page? $resource->component->page->name . '/' : '';
                $resourceParentName = $resource->component->parent? $resource->component->parent->name . '/' : '';
                $resourceComponentName = $resource->component? $resource->component->name . '/' : '';
                $resourceName = $resourcePageName .  $resourceParentName . $resourceComponentName . $resource->name;
                break;
            default:
                throw new Exception('Invalid resource type!');
                break;
        }
        return $resourceName;
    }
    public function getCascadeResourceOptions($request)
    {
        // TODO
        // need to re-consider the accessible resources
        // not get all assigned resources instead of get all resources
        $cascadeResourceOptions = [];

        // get cascade states and locations
        //$cascadeStatesLocations = $this->stateRepository->getCascadeLocations();
        $cascadeStatesLocations = $this->stateRepository->getAssignedCascadeLocations();
        $cascadeResourceOptions[] = array('value' => 1, 'label' => 'Location', 'children'  =>  $cascadeStatesLocations);

        // get cascade menus
        $cascadeMenus = $this->cMenuRepository->getCascadeMenus();
        $cascadeResourceOptions[] = array('value' => 2, 'label' => 'Menu', 'children'  =>  $cascadeMenus);

        // get cascade tabs (global tabs)
        $cascadeGlobalTabs = $this->tabRepository->getCascadeGlobalTabs();
        // get cascade tabs belong to certain pages
        $cascadeTabs = $this->pageRepository->getCascadeTabs();
        $cascadeAllTabs = array_merge($cascadeGlobalTabs, $cascadeTabs);
        $cascadeResourceOptions[] = array('value' => 3, 'label' => 'Tab', 'children'  =>  $cascadeAllTabs);

        // get cascade pages
        $cascadePages = $this->pageRepository->getCascadePages();
        $cascadeResourceOptions[] = array('value' => 4, 'label' => 'Page', 'children'  =>  $cascadePages);

        // get cascade components
        $cascadeComponents = $this->pageRepository->getCascadeComponents();
        $cascadeResourceOptions[] = array('value' => 5, 'label' => 'Component', 'children'  =>  $cascadeComponents);

        // get cascade processes
        $cascadeProcesses = $this->pageRepository->getCascadeProcesses();
        $cascadeResourceOptions[] = array('value' => 6, 'label' => 'Process', 'children'  =>  $cascadeProcesses);

        return $cascadeResourceOptions;
    }
}