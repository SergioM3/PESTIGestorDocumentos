<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Domain\Aggregates\Document;

class DocumentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Document::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->numberBetween(-10000, 10000),
            'documenttype_id' => $this->faker->numberBetween(-10000, 10000),
            'document_state' => $this->faker->word,
            'publish_date' => $this->faker->dateTime(),
            'create_date' => $this->faker->dateTime(),
            'update_date' => $this->faker->dateTime(),
            'delete_date' => $this->faker->dateTime(),
        ];
    }
}
