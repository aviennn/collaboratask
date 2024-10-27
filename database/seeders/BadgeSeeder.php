<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Badge;

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Badge;

class BadgeSeeder extends Seeder
{
    public function run()
    {
        Badge::updateOrCreate(
            ['name' => 'Beginner Badge'],
            [
                'description' => 'Awarded for completing 1 task.',
                'icon' => '/images/badges/beginner.png',
            ]
        );

        Badge::updateOrCreate(
            ['name' => 'Intermediate Badge'],
            [
                'description' => 'Awarded for completing 5 tasks.',
                'icon' => '/images/badges/intermediate.png',
            ]
        );

        Badge::updateOrCreate(
            ['name' => 'Expert Badge'],
            [
                'description' => 'Awarded for completing 28 tasks.',
                'icon' => '/images/badges/expert.png',
            ]
        );

        Badge::updateOrCreate(
            ['name' => 'Advanced Badge'],
            [
                'description' => 'Awarded for completing 30 tasks.',
                'icon' => '/images/badges/advanced.png',
            ]
        );

        Badge::updateOrCreate(
            ['name' => 'Berserk Badge'],
            [
                'description' => 'Awarded for completing 31 tasks.',
                'icon' => '/images/badges/berserk.png',
            ]
        );
    }
}
