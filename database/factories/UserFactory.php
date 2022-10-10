<?php

namespace Database\Factories;

use App\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->unique()->email,
            'password'   => 'HiWorld22#',
        ];
    }

    /**
     * Indicate that the user is a director.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function director()
    {
        return $this->state(function (array $attributes) {
            return [
                'type'  => UserType::DIRECTOR,
            ];
        });
    }

    /**
     * Indicate that the user is a manager.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function manager()
    {
        return $this->state(function (array $attributes) {
            return [
                'type'  => UserType::SHOP_MANAGER,
            ];
        });
    }

    /**
     * Indicate that the user is a staff.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function staff()
    {
        return $this->state(function (array $attributes) {
            return [
                'type'  => UserType::SHOP_STAFF,
            ];
        });
    }

    /**
     * Indicate that the user is an employee.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function employee()
    {
        return $this->state(function (array $attributes) {
            return [
                'type'  => UserType::UTAS_EMPLOYEE,
            ];
        });
    }

    /**
     * Indicate that the user is a student.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function student()
    {
        return $this->state(function (array $attributes) {
            return [
                'type'  => UserType::UTAS_STUDENT,
            ];
        });
    }
}
