<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Opinion;
use App\Models\OpinionReference;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['name' => 'guest', 'level' => 0],
            ['name' => 'user', 'level' => 1000],
            ['name' => 'admin', 'level' => 2000],
            ['name' => 'super', 'level' => 3000],
        ]);

        User::factory(100)->create();
        Topic::factory(100)->create()->each(function (Topic $topic) {
            $participants = User::all()->random(mt_rand(1, 5))->pluck('id');
            $topic->participants()->attach($participants);
        });

        Opinion::factory(100)->create();
        OpinionReference::factory(100)->create();
    }
}
