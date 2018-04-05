<?php
/**
 * Created by PhpStorm.
 * User: larry
 * Date: 3/5/17
 * Time: 12:15 AM
 */

namespace App\Models\Services;

use App\Models\Repositories\ProcessRepository;
use Illuminate\Http\Request;

class ProcessService extends BaseService
{
    protected $processRepository;

    public function __construct(ProcessRepository $processRepository)
    {
        $this->processRepository = $processRepository;
    }

    public function getAll($componentId, $user)
    {
        return $this->processRepository->getAll($componentId, $user);
    }
    public function getByPaginate(Request $request)
    {
        return $this->processRepository->getByPaginate($request);
    }
    public function update($request)
    {
        return $this->processRepository->save($request);
    }
    public function add($request)
    {
        return $this->processRepository->add($request);
    }
    public function delete($request)
    {
        return $this->processRepository->delete($request);
    }

}