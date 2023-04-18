<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Domain\Aggregates\Document;
use App\Domain\Aggregates\DownloadRequest;
use App\Domain\Aggregates\User;

class DownloadRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DownloadRequest::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'document_id' => Document::factory(),
            'user_id' => User::factory(),
            'request_state' => $this->faker->word,
            'accept_date' => $this->faker->dateTime(),
            'reject_date' => $this->faker->dateTime(),
        ];
    }
}
