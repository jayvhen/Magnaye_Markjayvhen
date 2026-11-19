<?php

namespace Database\Factories;

use App\Models\Shoe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Builds fake Shoe rows for seeding and testing.
 *
 * @extends Factory<Shoe>
 */
class ShoeFactory extends Factory
{
    /**
     * The default values of one fake shoe.
     */
    public function definition(): array
    {
        return [
            // A shoe-like name, for example "Crimson Runner"
            'shoe' => ucfirst(fake()->safeColorName()).' '.fake()->randomElement(['Runner', 'Sneaker', 'Loafer', 'Boot', 'Sandal', 'Slip-On', 'High-Top', 'Trainer']),
            // A random paragraph as the description
            'description' => fake()->paragraph(),
            // One of the four allowed status codes
            'status' => fake()->randomElement(['A', 'C', 'H', 'X']),
            // Creates a user for the shoe unless one is supplied (see recycle() below)
            'user_id' => User::factory(),
        ];
    }
}
