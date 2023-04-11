<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Domain\Aggregates\DocumentMetaData;

class DocumentMetaDataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DocumentMetaData::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'document_id' => $this->faker->numberBetween(-10000, 10000),
            'metatype_id' => $this->faker->numberBetween(-10000, 10000),
            'value' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
