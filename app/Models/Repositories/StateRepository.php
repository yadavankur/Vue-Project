<?php

namespace App\Models\Repositories;

use App\Models\Entities\ResourceType;
use App\Models\Entities\State;
use DB;
use Illuminate\Support\Facades\Auth;

class StateRepository extends BaseRepository
{
    public function model() { return 'App\Models\Entities\State';  }
    public function getAllStateNodes()
    {        return $this->model->where('active',1)->get(['*', DB::raw("'RW' as permission")])->keyBy('id')->toArray(); }
    public function add($request)
    {   $state =  new State();    $state->name = $request->input('name');  $state->comment = $request->input('comment');   
        $state->save();   return $state;
    }
    public function save($request)
    {
        $state =  $this->model->findOrFail($request->input('id'));  $state->name = $request->input('name');
        $state->comment = $request->input('comment');  $state->save();  return $state;
    }
    public function delete($request)
    {
        $state =  $this->model->findOrFail($request->input('id'));

        // 1) check whether this state has related locations
        $locations = $this->findLocations($state);
        if (count($locations) > 0)
        { throw new \Exception('You Cannot delete this state because it has related locations.');  }
        $state->active = 0;    $state->save();   return $state;
    }
    public function findLocations(State $state)
    {   return $state->locations;   }
    public function getCascadeLocations()
    {    $states = $this->model->where('active', 1)->with('locations')->get();
        // create cascade array
        $cascadeElements = [];
        foreach($states as $state)
        {
            if (count($state->locations) == 0) continue;
            $obj = new \stdClass();
            $obj->value = $state->id;
            $obj->label = $state->name;
            $obj->children = [];
            foreach($state->locations as $location)
            {
                $child = new \stdClass();
                $child->value = $location->id;
                $child->label = $location->name;
                $child->children = [];
                $obj->children[] = $child;
            }
            $cascadeElements[] = $obj;
        }
        return $cascadeElements;
    }

    public function getAssignedCascadeLocations()
    {

        // 1) check user role
        $user = Auth::user();   $isRoot = self::isRoot($user);
        if ($isRoot) 
        {   $states = $this->model->where('active', 1)->with('locations')->get();  // get all resources
            $cascadeElements = [];  // create cascade array
            foreach($states as $state)
            {   if (count($state->locations) == 0) continue;
                $obj = new \stdClass();
                $obj->value = $state->id;     $obj->label = $state->name;
                $obj->children = [];
                foreach($state->locations as $location)
                {   $child = new \stdClass();
                    $child->value = $location->id;
                    $child->label = $location->name;
                    $child->children = [];
                    $obj->children[] = $child;
                }
                $cascadeElements[] = $obj;
            }
            return $cascadeElements;
        }
        else
        {
            // 2) get accessible locations based on user
            $locationIds = LocationRepository::getAccessibleLocationIds($user);
            $query = $this->model->distinct()->select([
                DB::raw('states.id AS state_id'),
                DB::raw('states.name AS state_name'),
                DB::raw('locations.id AS location_id'),
                DB::raw('locations.name AS location_name'),
            ])
                    ->where('states.active', 1)->with('locations')
                    ->join('locations', 'locations.state_id', '=', 'states.id')
                    ->where('locations.active', 1)
                    ->wherein('locations.id', $locationIds)
                    ->orderBy('states.name', 'asc')
                    ->orderBy('locations.name', 'asc');
            $states = $query->get();

            $cascadeElements = [];
            foreach($states as $state)
            {
                //$obj = isset($cascadeElements[$state->state_id])? $cascadeElements[$state->state_id]: new \stdClass();
                if (!isset($cascadeElements[$state->state_id]))
                {
                    $obj = new \stdClass();
                    $obj->value = intval($state->state_id);
                    $obj->label = $state->state_name;
                    $obj->children = [];
                }
                else
                {
                    $obj = $cascadeElements[$state->state_id];
                }
                $child = new \stdClass();
                $child->value = intval($state->location_id);
                $child->label = $state->location_name;
                $child->children = [];
                $obj->children[] = $child;
                $cascadeElements[$state->state_id] = $obj;
            }
            $retElements = [];
            foreach($cascadeElements as $cascadeElement)
            {
                $retElements[] = $cascadeElement;
            }
            return $retElements;
        }
    }
}
