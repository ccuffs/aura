<?php

namespace Database\Factories;

use App\Models\App;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AppFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = App::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'secret' => 'password',
            'name' => $this->faker->sentence(2),
            'description' => $this->faker->sentence(20),
            'domain' => $this->faker->domainName,
        ];
    }
}
