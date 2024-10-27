<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Border;

class BorderSeeder extends Seeder
{
    public function run()
    {
        Border::create([
            'name' => 'Gold Border',
            'description' => 'Awarded for completing 100 tasks.',
            'image' => '/images/borders/gold-border.png',
        ]);

        Border::create([
            'name' => 'Platinum Border',
            'description' => 'Awarded for completing 200 tasks.',
            'image' => '/images/borders/platinum-border.png',
        ]);
    }
}
