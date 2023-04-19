<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetadataTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public static function run(): void
    {
        $data = [
            ['description' => 'Titulo'],
            ['description' => 'Abstract'],
            ['description' => 'Keywords'],
            ['description' => 'Author']
        ];
        DB::table('metadata_types')->insert($data);
    }
}
