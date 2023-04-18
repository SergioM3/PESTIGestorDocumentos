<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use App\Domain\Aggregates\Document;
use App\Domain\Aggregates\Document\DocumentType;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'user_id' => User::factory(),
            'document_type_id' => DocumentType::factory(),
            'document_state' => $this->faker->word,
            'publish_date' => $this->faker->dateTime(),
            'create_date' => $this->faker->dateTime(),
            'update_date' => $this->faker->dateTime(),
            'delete_date' => $this->faker->dateTime(),
        ];
    }
}
