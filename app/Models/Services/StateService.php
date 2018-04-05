<?php

namespace App\Models\Services;

use App\Models\Repositories\StateRepository;

class StateService extends BaseService
{
    protected $stateRepository;

    public function __construct(StateRepository $stateRepository) {  $this->stateRepository = $stateRepository;  }
    public function getAllStateNodes()  {   return $this->stateRepository->getAllStateNodes(); }
    public function update($request)   {  return $this->stateRepository->save($request);   }
    public function add($request)   {  return $this->stateRepository->add($request); }
    public function delete($request)   {  return $this->stateRepository->delete($request);  }
    public function getStateOptions()  { $stateOptions = $this->stateRepository->findAllBy('active',1); return $stateOptions;  }
    public function getCascadeLocations(){ $cascadeLocations = $this->stateRepository->getCascadeLocations(); return $cascadeLocations;}
    public function getAssignedCascadeLocationOptions()
    {    $assignedCascadeLocations = $this->stateRepository->getAssignedCascadeLocations(); return $assignedCascadeLocations; }

}