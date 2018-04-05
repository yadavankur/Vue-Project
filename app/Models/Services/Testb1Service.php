<?php

namespace App\Models\Services;

use App\Models\Repositories\Testb1Repository;

class Testb1Service extends BaseService
{
    protected $testb1Repository;
    
        public function __construct(Testb1Repository $testb1Repository) {  $this->testb1Repository = $testb1Repository;  }
        public function getTest1()  {   return $this->testb1Repository->getTest1(); }
        public function add($request)   {  return $this->testb1Repository->add($request); }
        public function delete($request)   {  return $this->testb1Repository->delete($request);  }
        public function update1($request)   {  return $this->testb1Repository->update1($request);   }

}