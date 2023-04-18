<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public static function run(): void
    {
        $data = [
            ['description' => 'Artigo Cientifico'],
            ['description' => 'Tese'],
            ['description' => 'Outro']
        ];
        DB::table('document_types')->insert($data);
    }
}
