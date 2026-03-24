<?php

namespace Database\Factories;

use App\Models\OffenseRule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IncidentReport>
 */
class IncidentReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reportTypes = ['Quick Log', 'Formal Charge'];
        $statuses = ['Submitted', 'Under Review by OSDW', 'Resolved', 'Dismissed'];

        $descriptions = [
            'Student was observed not wearing proper university ID during campus entry.',
            'Multiple instances of improper uniform attire documented during class hours.',
            'Student engaged in prohibited activity in campus facilities.',
            'Incident reported by faculty following classroom disruption.',
            'Security personnel observed violation during routine patrol.',
            'Staff confirmed policy breach during facility inspection.',
        ];

        return [
            'tracking_number' => 'INC-'.date('Y').'-'.strtoupper(Str::random(5)),
            'reporter_id' => User::factory(),
            'student_id' => User::factory(),
            'offense_id' => OffenseRule::factory(),
            'report_type' => fake()->randomElement($reportTypes),
            'description' => fake()->randomElement($descriptions),
            'evidence_path' => null,
            'status' => fake()->randomElement($statuses),
        ];
    }

    /**
     * Indicate that the report is a quick log.
     */
    public function quickLog(): static
    {
        return $this->state(fn (array $attributes) => [
            'report_type' => 'Quick Log',
            'evidence_path' => null,
        ]);
    }

    /**
     * Indicate that the report is a formal charge.
     */
    public function formalCharge(): static
    {
        return $this->state(fn (array $attributes) => [
            'report_type' => 'Formal Charge',
            'evidence_path' => 'confidential_evidence/'.fake()->uuid().'.pdf',
        ]);
    }

    /**
     * Indicate that the report is submitted.
     */
    public function submitted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Submitted',
        ]);
    }

    /**
     * Indicate that the report is under OSDW review.
     */
    public function underReview(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Under Review by OSDW',
        ]);
    }

    /**
     * Indicate that the report is resolved.
     */
    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Resolved',
        ]);
    }

    /**
     * Indicate that the report is dismissed.
     */
    public function dismissed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Dismissed',
        ]);
    }
}
