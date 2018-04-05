<?php

namespace App\Models\Repositories;


use App\Models\Entities\NoteType;
use Exception;
use DB;

class NoteTypeRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\NoteType';
    }
    public function getByPaginate($request)
    {
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];

        $perPage = $request->per_page;
        $search = $request->filter;

        $query = $this->model->select()
            ->where('note_types.active', 1)
            ->orderBy($sortBy, $sortDirection);

        if ($search) {
            $like = "%{$search}%";
            $query->where('note_types.name', 'LIKE', $like);
        }
        return $query->paginate($perPage);
    }
    public function save($request)
    {
        $type = $request->input('type'); //$request->type;
        $id = $request->input('id');
        $name = $request->input('name');
        $comment = $request->input('comment');
        $noteType = null;
        if ($id == 1)
        {
            throw new Exception('Sorry, you cannot update or delete this one!');
        }
        switch ($type)
        {
            case 'updated':
                $noteType = $this->model->findOrFail($id);
                if (!$noteType instanceof NoteType)
                {
                    throw new Exception('Invalid note type!');
                }

                $existedNoteType = NoteType::where('name', $name)
                    ->where('id', '<>',  $id)->active()->first();
                if ($existedNoteType instanceof NoteType)
                {
                    throw new Exception("Sorry, $name has already existed!");
                }

                $noteType->name = $name;
                $noteType->comment = $comment;
                $noteType->save();
                break;
            case 'added':
                // first check whether there are types in the database with the same name
                $noteType = NoteType::where('name', $name)->active()->first();
                if ($noteType instanceof NoteType)
                {
                    throw new Exception("Sorry, $name has already existed!");
                }
                $noteType = new NoteType();
                $noteType->name = $name;
                $noteType->comment = $comment;
                $noteType->active = 1;
                $noteType->save();
                break;
            case 'deleted':
                $noteType = $this->model->findOrFail($id);
                if (!$noteType instanceof NoteType)
                {
                    throw new Exception('Invalid note type!');
                }
                $noteType->active = 0;
                $noteType->save();
                break;
        }

        return $noteType;
    }
    public function getNoteTypeOptions($request)
    {
        $types = $this->model->select([DB::raw("id AS value"), DB::raw("name AS label")])
            ->where('active',1)
            ->orderBy('id', 'asc')->get();
        return $types;
    }
    public function getDowellNoteTypeOptions($request)
    {
        $types = $this->model->select([DB::raw("id AS value"), DB::raw("name AS label")])
            ->where('id', '<>', 1)
            ->where('active',1)
            ->orderBy('id', 'asc')->get();
        return $types;
    }

}