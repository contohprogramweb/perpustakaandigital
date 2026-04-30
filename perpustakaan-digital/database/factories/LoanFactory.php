<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $loanDate = fake()->dateTimeBetween('-30 days', 'now');
        $dueDate = Carbon::instance($loanDate)->addDays(14);
        
        return [
            'user_id' => User::factory(),
            'book_id' => Book::factory(),
            'loan_date' => $loanDate,
            'due_date' => $dueDate,
            'return_date' => null,
            'status' => 'borrowed',
            'notes' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the loan is returned.
     */
    public function returned(): static
    {
        return $this->state(fn (array $attributes) => [
            'return_date' => fake()->dateTimeBetween($attributes['loan_date'], 'now'),
            'status' => 'returned',
        ]);
    }

    /**
     * Indicate that the loan is overdue.
     */
    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'due_date' => fake()->dateTimeBetween('-30 days', '-1 day'),
            'status' => 'borrowed',
        ]);
    }
}
