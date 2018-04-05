<?php

namespace App\Models\Services;


use App\Models\Repositories\Cpm2Repository;
use Illuminate\Http\Request;

class Cpm2Service extends BaseService
{

    protected $cpm2Repository;

    public function __construct(Cpm2Repository $cpm2Repository)
    {        $this->cpm2Repository = $cpm2Repository;    }
    public function getByPaginate(Request $request){ return $this->cpm2Repository->getByPaginate($request); }
    //----------------------display data done------------------delete start now-----
    public function delete($request)  {  return $this->cpm2Repository->delete($request);   }
    //-----------delete record done----add record is below---------------
    public function add($request)  {  return $this->cpm2Repository->add($request);  }
//-------------add done---edit below-------------
    public function update($request)
    {    return $this->cpm2Repository->save($request); }

}