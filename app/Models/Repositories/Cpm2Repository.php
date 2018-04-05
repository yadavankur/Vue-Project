<?php
namespace App\Models\Repositories;


use App\Models\Entities\cpm2;
//use App\Models\Entities\CPM_Service;
use App\Models\Entities\Location;
use DB;
use Exception;
use Illuminate\Support\Facades\Auth;

class Cpm2Repository extends BaseRepository
{
    public function model()    {    return 'App\Models\Entities\cpm2';   
                               // return 'App\Models\Entities\CPM_Service';
                            }
    public function getByPaginate($request) //----used for display records from table
    {
        $sort = $request->sort;    $sort = explode('|', $sort);
        $sortBy = $sort[0];        $sortDirection = $sort[1];
        $perPage = $request->per_page;   $filter = $request->filter;
        $locationId = $filter['location'];    $search = $filter['filterText'];

        $query = $this->model->select('cpm2s.*')
            ->join('locations', 'locations.id', '=', 'cpm2s.location_id')
            ->orderBy($sortBy, $sortDirection)
            ->where('cpm2s.active',1)
            ->with('location');

        if ($locationId) {  $query->where('locations.id', $locationId);  }
                   else  {  $user = Auth::user();
                            $locationIds = LocationRepository::getAccessibleLocationIds($user);
                            $query->wherein('locations.id', $locationIds);
                         }

        if ($search) { $like = "%{$search}%";
                       $query = $query->where(function ($query) use ($like) { $query->where('cpm2s.name', 'LIKE', $like);    });
                     }

        return $query->paginate($perPage);
    }
    //----------------------getting of data finished above-----now delete start-----
    public function delete($request)
    {
        $cpm2Service =  $this->model->findOrFail($request->input('id'));
        $cpm2Service->active = 0;
        $cpm2Service->save();
        return $cpm2Service;
    }
    //------------------------------    
    public function add($request)
    {
        $cpm2Service =  new cpm2();
        $cpm2Service->name = $request->input('name');
        $cpm2Service->comment = $request->input('comment');
        $locationName = $request->input('location.name');
        $location = Location::where('name', $locationName)->where('active',1)->first();
        if (! $location instanceof Location)
        {    throw new Exception('The location is invalid!');  }
        $cpm2Service->location_id =$location->id;
        $cpm2Service->save();
        return $cpm2Service;
    }

    public function save($request)
    {
        $cpm2Service =  $this->model->findOrFail($request->input('id'));
        $cpm2Service->name = $request->input('name');
        $locationName = $request->input('location.name');
        $location = Location::where('name', $locationName)->where('active',1)->first();
        if (! $location instanceof Location)
        {            throw new Exception('The location is invalid!');        }
        $cpm2Service->location_id =$location->id;
        $cpm2Service->comment = $request->input('comment');
        $cpm2Service->save();
        return $cpm2Service;
    }

}