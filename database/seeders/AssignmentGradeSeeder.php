<?php

namespace Database\Seeders;

use App\Models\AssignmentGrade;
use App\Models\AssignmentSubmission;
use Illuminate\Database\Seeder;

class AssignmentGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy các bài nộp chưa có điểm
        $submissionsWithoutGrades = AssignmentSubmission::whereDoesntHave('grade')
            ->inRandomOrder()
            ->take(10) // Số lượng điểm muốn tạo, ví dụ 10
            ->get();

        if ($submissionsWithoutGrades->isEmpty()) {
            $this->command->info('No assignment submissions found without grades to seed.');

            return;
        }

        $submissionsWithoutGrades->each(function (AssignmentSubmission $submission) {
            AssignmentGrade::factory()->create(['submission_id' => $submission->id]);
        });

        $this->command->info('Created '.$submissionsWithoutGrades->count().' assignment grades for submissions without existing grades.');
    }
}
