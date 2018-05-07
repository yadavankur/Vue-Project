<?php

namespace App\Models\Services;

use App\Models\Repositories\TicketErrorTypeRepository;

class TicketErrorTypeService extends BaseService
{
    protected $TicketErrorTypeRepository;
    
    public function __construct(TicketErrorTypeRepository $TicketErrorTypeRepository) {  $this->TicketErrorTypeRepository = $TicketErrorTypeRepository;  }
    public function getTicketErrorTypeTable()   {  return $this->TicketErrorTypeRepository->getTicketErrorTypeTable(); }
    public function addTicketErrorTypeTable($request)   {  return $this->TicketErrorTypeRepository->addTicketErrorTypeTable($request); }
    public function deleteTicketErrorTypeTable($request) {  return $this->TicketErrorTypeRepository->deleteTicketErrorTypeTable($request);  }
    public function updateTicketErrorTypeTable($request) {  return $this->TicketErrorTypeRepository->updateTicketErrorTypeTable($request);   }
        
}