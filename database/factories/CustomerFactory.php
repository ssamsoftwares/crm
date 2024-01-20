<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'=> 1,
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone_number' => fake()->numerify('##########'),
            'company_name'=> fake()->company(),
            'status'=> Null,
            'communication_medium'=> 'phone',
            'alloted_date' => fake()->date(),
            'project_details'=> fake()->sentence()

        ];
    }
}
