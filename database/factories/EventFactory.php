<?php 

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'location' => $this->faker->city,
            'date' => $this->faker->dateTime,
            'capacity' => $this->faker->numberBetween(5, 100),
        ];
    }
}
