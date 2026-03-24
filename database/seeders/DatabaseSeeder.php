<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call([
            RoleSeeder::class,
            CSUOffenseRuleSeeder::class,
        ]);

        // Create Administrator account
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);
        $admin->assignRole('administrator');

        // Create Staff account
        $staff = User::factory()->create([
            'name' => 'Staff Member',
            'email' => 'staff@example.com',
            'password' => 'password',
        ]);
        $staff->assignRole('staff');

        // Create Student accounts
        $student1 = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'student@example.com',
            'password' => 'password',
        ]);
        $student1->assignRole('student');

        $student2 = User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'student2@example.com',
            'password' => 'password',
        ]);
        $student2->assignRole('student');

        $student3 = User::factory()->create([
            'name' => 'Mike Johnson',
            'email' => 'student3@example.com',
            'password' => 'password',
        ]);
        $student3->assignRole('student');

        // Temporary Student account
        $student4 = User::factory()->create([
            'name' => 'Temp Student',
            'email' => 'tempstudent@example.com',
            'password' => 'password',
        ]);
        $student4->assignRole('student');

        $this->command->info('Test accounts created successfully!');
        $this->command->info('Administrator: admin@example.com / password');
        $this->command->info('Staff: staff@example.com / password');
        $this->command->info('Students: student@example.com, student2@example.com, student3@example.com, tempstudent@example.com / password');
    }
}
