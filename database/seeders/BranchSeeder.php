<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            ['name' => 'Chilonzor-1', 'location' => 'Osiyo-33'],
            ['name' => 'Chilonzor-2', 'location' => 'kv15-3'],
            ['name' => 'Yunusobod-1', 'location' => 'Sayyor-133'],
            ['name' => 'Yunusobod-2', 'location' => 'Dostlik-73'],
            ['name' => 'Mirzo Ulug\'bek-1', 'location' => 'Bahromov-3D'],
            ['name' => 'Yashnobod-1', 'location' => 'Nurzor-3a'],
            ['name' => 'Yangihayot-1', 'location' => 'Husn-333L'],
            ['name' => 'Sergeli-1', 'location' => 'Sayram-33A'],
            ['name' => 'Yakkasaroy-1', 'location' => 'Sayram-C33'],
            ['name' => 'Shayhontohur-1', 'location' => 'Bayram-93'],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }

    }
}
