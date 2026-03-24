<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OffenseRule>
 */
class OffenseRuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['Academic', 'Behavioral', 'Procedural', 'Safety', 'Technology'];
        $severityLevels = ['Minor', 'Moderate', 'Major', 'Severe'];

        $offenseTemplates = [
            'Academic' => [
                ['title' => 'Academic Dishonesty', 'sanction' => 'Written Warning'],
                ['title' => 'Plagiarism', 'sanction' => 'Grade Penalty'],
                ['title' => 'Cheating on Examination', 'sanction' => 'Course Failure'],
                ['title' => 'Falsification of Records', 'sanction' => 'Suspension'],
            ],
            'Behavioral' => [
                ['title' => 'Disruptive Conduct', 'sanction' => 'Written Warning'],
                ['title' => 'Verbal Harassment', 'sanction' => 'Probation'],
                ['title' => 'Fighting or Physical Altercation', 'sanction' => 'Suspension'],
                ['title' => 'Threatening Behavior', 'sanction' => 'Immediate Suspension'],
            ],
            'Procedural' => [
                ['title' => 'Failure to Comply with Institutional Policy', 'sanction' => 'Written Warning'],
                ['title' => 'Unauthorized Entry', 'sanction' => 'Probation'],
                ['title' => 'Misuse of Identification', 'sanction' => 'Disciplinary Action'],
            ],
            'Safety' => [
                ['title' => 'Violation of Safety Protocols', 'sanction' => 'Written Warning'],
                ['title' => 'Possession of Prohibited Items', 'sanction' => 'Confiscation and Warning'],
                ['title' => 'Endangering Others', 'sanction' => 'Suspension'],
            ],
            'Technology' => [
                ['title' => 'Unauthorized Access to Systems', 'sanction' => 'Account Suspension'],
                ['title' => 'Cyberbullying', 'sanction' => 'Probation'],
                ['title' => 'Misuse of Institutional Technology', 'sanction' => 'Written Warning'],
            ],
        ];

        $category = fake()->randomElement($categories);
        $template = fake()->randomElement($offenseTemplates[$category]);
        $severity = fake()->randomElement($severityLevels);

        // Generate unique code based on category and random number
        $categoryCode = strtoupper(substr($category, 0, 2));
        $code = $categoryCode.'-'.fake()->unique()->numberBetween(100, 999);

        // Auto-assign gravity based on severity_level
        $gravity = match ($severity) {
            'Minor' => 'minor',
            'Moderate', 'Major', 'Severe' => 'major',
            default => 'other',
        };

        return [
            'code' => $code,
            'title' => $template['title'],
            'description' => fake()->paragraph(3),
            'category' => $category,
            'severity_level' => $severity,
            'gravity' => $gravity,
            'standard_sanction' => $template['sanction'],
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the offense rule is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Create a minor offense.
     */
    public function minor(): static
    {
        return $this->state(fn (array $attributes) => [
            'severity_level' => 'Minor',
            'gravity' => 'minor',
        ]);
    }

    /**
     * Create a severe offense.
     */
    public function severe(): static
    {
        return $this->state(fn (array $attributes) => [
            'severity_level' => 'Severe',
            'gravity' => 'major',
        ]);
    }
}
