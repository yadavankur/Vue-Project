<?php

namespace App\Models\Services;

use App\Models\Repositories\PageRepository;
use Illuminate\Http\Request;

class PageService extends BaseService
{
    protected $pageRepository;

    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    public function getPageByName($pageName)
    {
        return $this->pageRepository->getPageByName($pageName);
    }
    public function getPageOptions()
    {
        $pageOptions = $this->pageRepository->findAllBy('active',1);
        return $pageOptions;
    }
    public function getAll($user)
    {
        return $this->pageRepository->getAll($user);
    }
    public function getByPaginate(Request $request)
    {
        return $this->pageRepository->getByPaginate($request);
    }
    public function update($request)
    {
        return $this->pageRepository->save($request);
    }
    public function add($request)
    {
        return $this->pageRepository->add($request);
    }
    public function delete($request)
    {
        return $this->pageRepository->delete($request);
    }
    public function getCascadeComponents()
    {
        return $this->pageRepository->getCascadeComponents();
    }

}