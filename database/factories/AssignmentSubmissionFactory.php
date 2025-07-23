<?php

namespace Database\Factories;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AssignmentSubmission>
 */
class AssignmentSubmissionFactory extends Factory
{
    use Concerns\HasAssignsRandomOrNewModel,
        Concerns\HasFakesStatus;

    protected $model = AssignmentSubmission::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'assignment_id' => $this->assignRandomOrNewModel(Assignment::class),
            'user_id' => $this->assignRandomOrNewModel(User::class),
            'submission_text' => fake()->paragraph(),
            'submission_date' => now(),
            // 'status' => $this->fakeStatus(),
        ];
    }
}
