<?php

namespace App\Models\Repositories;

use App\Models\Entities\Menu;
use DB;
use Exception;

class MenuRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\Menu';
    }
    public function add($request)
    {
        $menu =  new Menu();
        $menu->parent_id = $request->input('parent_id');
        $menu->name = $request->input('name');
        $menu->link = $request->input('link');
        $menu->comment = $request->input('comment');
        $menu->save();
        return $menu;
    }
    public function save($request)
    {
        $menu =  $this->model->findOrFail($request->input('id'));
        $menu->name = $request->input('name');
        $menu->link = $request->input('link');
        $menu->comment = $request->input('comment');
        $menu->save();
        return $menu;
    }
    public function delete($request)
    {
        $menu =  $this->model->findOrFail($request->input('id'));

        // 1) check whether this menu has children if yes cannot be deleted
        $childrenMenu = $this->findAllBy('parent_id', $menu->id);
        if (count($childrenMenu) > 0)
        {
            throw new Exception('You Cannot delete this menu because it has children menus.');
        }
        $menu->active = 0;
        $menu->save();
        return $menu;
    }
}
