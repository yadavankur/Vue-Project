<?php

namespace App\Models\Repositories;


use App\Models\Entities\NoteTypeAction;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use DB;

class NoteTypeActionRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\NoteTypeAction';
    }
    public function getByPaginate($request)
    {
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];

        $perPage = $request->per_page;
        $locationId = $request->locationId;
        $noteTypeId = $request->noteTypeId;

        $query = $this->model->select([
            'note_type_actions.id',
            'note_type_actions.location_id',
            'note_type_actions.note_type_id',
            'note_type_actions.action',
            'note_type_actions.comment',
            DB::raw("note_types.name as note_type_name"),
            DB::raw("locations.name as location_name")
        ])
            ->join('note_types', 'note_types.id', '=', 'note_type_actions.note_type_id')
            ->join('locations', 'locations.id', '=', 'note_type_actions.location_id')
            ->where('note_type_actions.active', 1)
            ->where('note_types.active', 1)
            ->where('locations.active', 1)
            ->with('location')
            ->with('note_type')
            ->orderBy($sortBy, $sortDirection);

        if ($locationId) {
            $query->where('note_type_actions.location_id', $locationId);
        }
        else
        {
            // get accessible locations
            $user = JWTAuth::parseToken()->authenticate();
            $locationIds = LocationRepository::getAccessibleLocationIds($user);
            $query->wherein('note_type_actions.location_id', $locationIds);
        }
        if ($noteTypeId)
        {
            $query->where('note_type_actions.note_type_id', $noteTypeId);
        }

        return $query->paginate($perPage);
    }
    public function save($request)
    {
        $type = $request->input('type'); //$request->type;
        $id = $request->input('id');
        $locationId = $request->input('location_id');
        $noteTypeId = $request->input('note_type_id');
        $action = $request->input('action');
        $comment = $request->input('comment');
        $noteTypeAction = null;

        switch ($type)
        {
            case 'updated':
                $noteTypeAction = $this->model->findOrFail($id);
                if (!$noteTypeAction instanceof NoteTypeAction)
                {
                    throw new Exception('Invalid note type action!');
                }
                $existedNoteType = NoteTypeAction::where('location_id', $locationId)
                    ->where('note_type_id', $noteTypeId)
                    ->where('id', '<>',  $id)->active()->first();
                if ($existedNoteType instanceof NoteTypeAction)
                {
                    throw new Exception("Sorry, the action for this location and note type has already existed!");
                }

                $noteTypeAction->location_id = $locationId;
                $noteTypeAction->note_type_id = $noteTypeId;
                $noteTypeAction->action = $action;
                $noteTypeAction->comment = $comment;
                $noteTypeAction->save();
                break;
            case 'added':
                // first check whether there are types in the database with the same name
                $noteTypeAction = NoteTypeAction::where('location_id', $locationId)
                    ->where('note_type_id', $noteTypeId)->active()->first();
                if ($noteTypeAction instanceof NoteTypeAction)
                {
                    throw new Exception("Sorry, the action for this location and note type has already existed!");
                }
                $noteTypeAction = new NoteTypeAction();
                $noteTypeAction->location_id = $locationId;
                $noteTypeAction->note_type_id = $noteTypeId;
                $noteTypeAction->action = $action;
                $noteTypeAction->comment = $comment;
                $noteTypeAction->save();
                break;
            case 'deleted':
                $noteTypeAction = $this->model->findOrFail($id);
                if (!$noteTypeAction instanceof NoteTypeAction)
                {
                    throw new Exception('Invalid note type action!');
                }
                $noteTypeAction->active = 0;
                $noteTypeAction->save();
                break;
        }
        return $noteTypeAction;
    }
}