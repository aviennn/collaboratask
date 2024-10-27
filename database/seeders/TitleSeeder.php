<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Title;

class TitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $titles = [
            ['name' => 'The Owl', 'description' => 'For the late-night users.', 'icon' => 'icons/owl.png'],
            ['name' => 'Caffeine Overdose', 'description' => 'For the coffee lovers.', 'icon' => 'icons/coffee.png'],
            ['name' => 'Bug Squasher', 'description' => 'For the problem solvers.', 'icon' => 'icons/bug.png'],
            ['name' => 'Master of Disguise', 'description' => 'For the frequent profile changers.', 'icon' => 'icons/mask.png'],
            ['name' => 'The Lurker', 'description' => 'For those who observe quietly.', 'icon' => 'icons/eye.png'],
        ];

        foreach ($titles as $title) {
            Title::create($title);
        }
    }
}
