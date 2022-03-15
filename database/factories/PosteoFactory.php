<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PosteoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'userID' => $this->faker->numberBetween(1, 10),
            'like' => $this->faker->numerify('##'),
            'contenido' => $this->faker->text(),
        ];
    }
}
