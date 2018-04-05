<?php

namespace App\Models\Services;


use App\Models\Repositories\NoteTypeActionRepository;

class NoteTypeActionService extends BaseService
{
    protected $noteTypeActionRepository;

    public function __construct(NoteTypeActionRepository $noteTypeActionRepository)
    {
        $this->noteTypeActionRepository = $noteTypeActionRepository;
    }
    public function getByPaginate($request)
    {
        return $this->noteTypeActionRepository->getByPaginate($request);
    }
    public function update($request)
    {
        return $this->noteTypeActionRepository->save($request);
    }
}