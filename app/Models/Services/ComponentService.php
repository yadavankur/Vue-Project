<?php
namespace App\Models\Services;

use App\Models\Entities\Page;
use App\Models\Repositories\ComponentRepository;
use Illuminate\Http\Request;

class ComponentService extends BaseService
{
    protected $componentRepository;

    public function __construct(ComponentRepository $componentRepository)
    {
        $this->componentRepository = $componentRepository;
    }
    public function getAll($pageId, $user)
    {
        return $this->componentRepository->getAll($pageId, $user);
    }
    public function getComponentByName($componentName)
    {
        return $this->componentRepository->getComponentByName($componentName);
    }
    public function getByPaginate(Request $request)
    {
        return $this->componentRepository->getByPaginate($request);
    }
    public function update($request)
    {
        return $this->componentRepository->save($request);
    }
    public function add($request)
    {
        return $this->componentRepository->add($request);
    }
    public function delete($request)
    {
        return $this->componentRepository->delete($request);
    }
    public function getComponentOptions(Request $request)
    {
        $selectedPageName = $request->selectedPage;
        $selectedPage = Page::where('name', $selectedPageName)->first();
        $where = array(
            'active' => 1,
            'page_id' => $selectedPage->id
            );
        $componentOptions = $this->componentRepository->findWhere($where);
        return $componentOptions;
    }
    public function getAllComponentOptions(Request $request)
    {
        $componentOptions = $this->componentRepository->getAllComponentOptions();
        return $componentOptions;
    }
    public function getCascadeComponentOptions()
    {
        return $this->componentRepository->getCascadeComponentOptions();
    }
}