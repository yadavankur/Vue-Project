<?php

namespace App\Models\Services;

use App\Models\Repositories\TicketTypeRepository;

class TicketTypeService extends BaseService
{
    protected $TicketTypeRepository;
    
        public function __construct(TicketTypeRepository $TicketTypeRepository) {  $this->TicketTypeRepository = $TicketTypeRepository;  }
        public function getTicketTypeTable()  {   return $this->TicketTypeRepository->getTicketTypeTable(); }
        public function addTicketTypeTable($request)   {  return $this->TicketTypeRepository->addTicketTypeTable($request); }
        public function deleteTicketTypeTable($request) {  return $this->TicketTypeRepository->deleteTicketTypeTable($request);  }
        public function updateTicketTypeTable($request) {  return $this->TicketTypeRepository->updateTicketTypeTable($request);   }
      
}