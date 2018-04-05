<?php

use Illuminate\Database\Seeder;
use App\Models\Entities\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        $users = factory(User::class, 10)->create();
    }
}
