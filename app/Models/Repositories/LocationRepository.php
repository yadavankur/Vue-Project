<?php
namespace App\Models\Repositories;

use App\Models\Entities\Location;
use App\Models\Entities\ResourceType;
use App\Models\Entities\State;
use App\Models\Services\UserService;
use Exception;
use DB;

class LocationRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\Location';
    }

    public function getAll($user)
    {
        $isAdmin = self::isAdmin($user);
        if ($isAdmin)
            return $this->model->where('active',1)->get(['*', DB::raw("'RW' as permission")])->keyBy('id')->toArray();
        else
            return $this->model->where('active',1)->get(['*', DB::raw("'H' as permission")])->keyBy('id')->toArray();

    }
    public function getByPaginate($request)
    {
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];

        $perPage = $request->per_page;

        $search = $request->filter;

        $query = $this->model->select('locations.*')
            ->join('states', 'states.id', '=', 'locations.state_id')
            ->orderBy($sortBy, $sortDirection)
            ->where('locations.active',1)->with('state');

        if ($search) {
            $like = "%{$search}%";

            $query = $query->where(function ($query) use ($like) {
                $query->where('locations.name', 'LIKE', $like)
                    ->where('locations.abbreviation', 'LIKE', $like)
                    ->orWhere('states.name','LIKE', $like);
            });
        }
        return $query->paginate($perPage);
//        $subQuery = $this->model->select('locations.*')
//            ->join('states', 'states.id', '=', 'locations.state_id')
//            ->where('active',1);

//        $data = DB::table(DB::raw("({$subQuery->toSql()}) as t"))
//            ->mergeBindings($subQuery->getQuery())
//            ->orderBy($sortBy, $sortDirection)
//            ->get();

//        $page = $request->page;
//        $offset = ($page * $perPage) - $perPage;
//
//        $products = new LengthAwarePaginator(
//            array_slice($data, $offset, $perPage, true),
//            count($data),
//            $perPage,
//            $page,
//            ['path' => $request->url(), 'query' => $request->query()]
//        );

    }
    public function add($request)
    {
        $location =  new Location();
        $location->name = $request->input('name');
        $location->abbreviation = $request->input('abbreviation');
        $location->comment = $request->input('comment');
        $stateName = $request->input('state.name');
        $state = State::where('name', $stateName)->where('active',1)->first();
        if (! $state instanceof State)
        {
            throw new Exception('The state is invalid!');
        }
        $location->state_id =$state->id;
        $location->save();
        return $location;
    }
    public function save($request)
    {
        $location =  $this->model->findOrFail($request->input('id'));
        $location->name = $request->input('name');
        $location->abbreviation = $request->input('abbreviation');
        $location->comment = $request->input('comment');
        $stateName = $request->input('state.name');
        $state = State::where('name', $stateName)->where('active',1)->first();
        if (! $state instanceof State)
        {
            throw new Exception('The state is invalid!');
        }
        $location->state_id =$state->id;
        $location->save();
        return $location;
    }
    public function delete($request)
    {
        $location =  $this->model->findOrFail($request->input('id'));
        $location->active = 0;
        $location->save();
        return $location;
    }

    public static function getAccessibleLocationIds($user, $userService = null)
    {
        // 1) check user role
        $isRoot = self::isRoot($user);
        //$isAdmin = self::isAdmin($user);
        if ($isRoot)
            // get all locations
            $aclLocations = Location::where('active', 1)->get()->toArray();
        else if ($userService)
            // 2) get accessible locations based on user
            $aclLocations = $userService->getAclResourceByType($user, ResourceType::LOCATION);
        else
            $aclLocations = UserService::getAclResource($user, ResourceType::LOCATION);
        foreach($aclLocations as $aclLocation)
        {
            $aclRets[] = $aclLocation;
        }
        $locationIds = array_column($aclRets, 'id');
        $locationIds = array_unique($locationIds);
        return $locationIds;
    }

}