<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Domain\Aggregates\Document;
use App\Domain\Aggregates\DocumentMetadata;
use App\Domain\Aggregates\MetadataType;

class DocumentMetadataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DocumentMetadata::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'document_id' => Document::factory(),
            'metadata_type_id' => MetadataType::factory(),
            'value' => $this->faker->word,
        ];
    }
}
