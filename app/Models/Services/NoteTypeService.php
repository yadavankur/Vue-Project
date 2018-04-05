<?php

namespace App\Models\Services;


use App\Models\Repositories\NoteTypeRepository;

class NoteTypeService extends BaseService
{
    protected $noteTypeRepository;

    public function __construct(NoteTypeRepository $noteTypeRepository)
    {
        $this->noteTypeRepository = $noteTypeRepository;
    }
    public function getByPaginate($request)
    {
        return $this->noteTypeRepository->getByPaginate($request);
    }
    public function update($request)
    {
        return $this->noteTypeRepository->save($request);
    }
    public function getNoteTypeOptions($request)
    {
        return $this->noteTypeRepository->getNoteTypeOptions($request);
    }
    public function getDowellNoteTypeOptions($request)
    {
        return $this->noteTypeRepository->getDowellNoteTypeOptions($request);
    }
}