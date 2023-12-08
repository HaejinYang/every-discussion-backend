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
        Topic::factory(20)->create()->each(function (Topic $topic) {
            $participants = User::where('id', '<', 10)->inRandomOrder()->take(10)->get()->pluck('id');
            $topic->participants()->attach($participants);

            $participants->map(function (int $id) use ($topic) {
                Opinion::factory(1)->create(['topic_id' => $topic->id, 'user_id' => $id]);
            });
        });

        OpinionReference::factory(100)->create();

        $this->call([AdminSeeder::class]);
    }
}
