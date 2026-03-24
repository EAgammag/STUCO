<?php

namespace Database\Factories;

use App\Models\OffenseRule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ViolationRecord>
 */
class ViolationRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['Pending Review', 'Sanction Active', 'Resolved', 'Appealed'];
        $status = fake()->randomElement($statuses);

        $incidentDate = fake()->dateTimeBetween('-2 years', 'now');

        $incidentDescriptions = [
            'Student was observed engaging in prohibited activity during class hours.',
            'Incident occurred in the institutional facility and was reported by faculty.',
            'Multiple witnesses confirmed the violation of institutional policy.',
            'Student admitted to the conduct during initial interview.',
            'Reported by staff member following direct observation of the incident.',
            'Security footage confirmed the violation occurred on the specified date.',
        ];

        return [
            'case_tracking_number' => 'CSU-'.now()->year.'-'.now()->format('m').'-'.str_pad((string) fake()->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'student_id' => User::factory(),
            'offense_id' => OffenseRule::factory(),
            'reported_by' => User::factory(),
            'status' => $status,
            'incident_description' => fake()->randomElement($incidentDescriptions),
            'date_of_incident' => $incidentDate,
            'resolution_date' => $status === 'Resolved' ? fake()->dateTimeBetween($incidentDate, 'now') : null,
            'resolution_notes' => $status === 'Resolved' ? fake()->paragraph(2) : null,
            'sanction_imposed_at' => in_array($status, ['Sanction Active', 'Resolved']) ? fake()->dateTimeBetween($incidentDate, 'now') : null,
            'sanction_effective_at' => in_array($status, ['Sanction Active', 'Resolved']) ? fake()->dateTimeBetween($incidentDate, 'now') : null,
            'settled_by' => in_array($status, ['Sanction Active', 'Resolved']) ? fake()->randomElement(['Dean', 'OSDW']) : null,
            'action_taken' => in_array($status, ['Sanction Active', 'Resolved']) ? fake()->sentence(10) : null,
        ];
    }

    /**
     * Indicate that the violation is pending review.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Pending Review',
            'resolution_date' => null,
            'resolution_notes' => null,
        ]);
    }

    /**
     * Indicate that the violation has an active sanction.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Sanction Active',
            'resolution_date' => null,
            'resolution_notes' => null,
        ]);
    }

    /**
     * Indicate that the violation has been resolved.
     */
    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Resolved',
            'resolution_date' => fake()->dateTimeBetween($attributes['date_of_incident'], 'now'),
            'resolution_notes' => 'Sanction completed. Student has fulfilled all requirements.',
        ]);
    }

    /**
     * Indicate that the violation has been appealed.
     */
    public function appealed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Appealed',
            'resolution_date' => null,
            'resolution_notes' => null,
        ]);
    }

    /**
     * Set a specific student for the violation.
     */
    public function forStudent(User $student): static
    {
        return $this->state(fn (array $attributes) => [
            'student_id' => $student->id,
        ]);
    }

    /**
     * Set a specific reporter for the violation.
     */
    public function reportedBy(User $reporter): static
    {
        return $this->state(fn (array $attributes) => [
            'reported_by' => $reporter->id,
        ]);
    }
}
