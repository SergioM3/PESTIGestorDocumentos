<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public static function run(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $data = [
                        "user_id" => Factory::create()->numberBetween(1, \App\Models\User::count()),
                        "document_type_id" => Factory::create()->numberBetween(1, \App\Domain\Aggregates\Document\DocumentType::count()),
                        'document_state' => 'Pending',
                        'publish_date' => Factory::create()->dateTime(),
                        'create_date' => Factory::create()->dateTime()
                    ];
            DB::table('documents')->insert($data);
        }
    }
}
