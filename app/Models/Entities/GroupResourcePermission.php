<?php

namespace App\Models\Entities;

class GroupResourcePermission extends BaseModel
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'group_resource_permission';

    /**
     * Get the resource type
     */
    public function resourceType()
    {
        return $this->belongsTo(ResourceType::class)->where('active',1);
    }
    /**
     * Get the groups that has the group.
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class)->where('active',1);
    }
    /**
     * Get the permissions that has the group.
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class)->where('active',1);
    }
    /**
     * Get all of the owning resource models.
     */
    public function resource()
    {

        $query = $this->morphTo('resource', 'resource_type_id', 'resource_id')->where('active',1);;
        return $query;

//            ->with([
//                'state' => function($query){
//                                $query->where('resource_type_id', 1);
//                            },
//                'page' => function($query){
//                                $query->where('resource_type_id', 3)->orwhere('resource_type_id', 5);
//                            },
//                'parent' => function($query){
//                                $query->where('resource_type_id', 5);
//                            },
//                'component' => function($query){
//                                $query->where('resource_type_id', 6);
//                            },
//            ]);
    }
}
