<?php

namespace App\Models\Repositories;


use App\Models\Entities\TaskMapping;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class TaskMappingRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\TaskMapping';
    }
    public function getByPaginate($request)
    {
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];

        $perPage = $request->per_page;
        $locationId = $request->input('filter.location.id');

        $query = $this->model->select()
            ->where('active', 1)
            ->with('activity')
            ->with('location')
            ->orderBy($sortBy, $sortDirection);

        if ($locationId) {
            $query->where('task_mappings.location_id', $locationId);
        }
        else
        {
            // get accessible locations
            $user = JWTAuth::parseToken()->authenticate();
            $locationIds = LocationRepository::getAccessibleLocationIds($user);
            $query->wherein('task_mappings.location_id', $locationIds);
        }

        return $query->paginate($perPage);
    }
    public function save($request)
    {
        $type = $request->input('type');
        $id = $request->input('id');
        $locationId = $request->input('location.id');
        $activityId = $request->input('activity.id');
        $taskName = $request->input('task_name');
        $comment = $request->input('comment');
        $taskMapping = null;

        switch ($type)
        {
            case 'updated':
                $taskMapping = $this->model->findOrFail($id);
                if (!$taskMapping instanceof TaskMapping)
                {
                    throw new Exception('Invalid task mapping!');
                }
                $existedNoteType = TaskMapping::where('location_id', $locationId)
                    ->where('activity_id', $activityId)
                    ->where('id', '<>',  $id)->active()->first();
                if ($existedNoteType instanceof TaskMapping)
                {
                    throw new Exception("Sorry, the mapping for this activity has already existed!");
                }

                $taskMapping->location_id = $locationId;
                $taskMapping->activity_id = $activityId;
                $taskMapping->task_name = $taskName;
                $taskMapping->comment = $comment;
                $taskMapping->save();
                break;
            case 'added':
                // first check whether there are types in the database with the same name
                $taskMapping = TaskMapping::where('location_id', $locationId)
                    ->where('activity_id', $activityId)->active()->first();
                if ($taskMapping instanceof TaskMapping)
                {
                    throw new Exception("Sorry, the mapping for this activity has already existed!");
                }
                $taskMapping = new TaskMapping();
                $taskMapping->location_id = $locationId;
                $taskMapping->activity_id = $activityId;
                $taskMapping->task_name = $taskName;
                $taskMapping->comment = $comment;
                $taskMapping->save();
                break;
            case 'deleted':
                $taskMapping = $this->model->findOrFail($id);
                if (!$taskMapping instanceof TaskMapping)
                {
                    throw new Exception('Invalid task mapping!');
                }
                $taskMapping->active = 0;
                $taskMapping->save();
                break;
        }
        return $taskMapping;
    }
    public static function getMappedTask($taskName, $locationId)
    {
        $taskMapping = TaskMapping::where('task_name', $taskName)
            ->where('location_id', $locationId)->active()->first();
        return $taskMapping;
    }
    public static function getMappedTaskByActivity($activityId, $locationId)
    {
        $taskMapping = TaskMapping::where('activity_id', $activityId)
            ->where('location_id', $locationId)->active()->first();
        return $taskMapping;
    }
}