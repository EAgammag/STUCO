<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
            // General Settings
            ['group' => 'general', 'key' => 'app_name', 'value' => 'CSU Student Conduct Management System', 'type' => 'string'],
            ['group' => 'general', 'key' => 'timezone', 'value' => 'Asia/Manila', 'type' => 'string'],
            ['group' => 'general', 'key' => 'date_format', 'value' => 'F j, Y', 'type' => 'string'],
            ['group' => 'general', 'key' => 'academic_year', 'value' => '2025-2026', 'type' => 'string'],

            // Security Settings
            ['group' => 'security', 'key' => 'session_timeout', 'value' => '120', 'type' => 'integer'],
            ['group' => 'security', 'key' => 'max_login_attempts', 'value' => '5', 'type' => 'integer'],
            ['group' => 'security', 'key' => 'lockout_duration', 'value' => '15', 'type' => 'integer'],
            ['group' => 'security', 'key' => 'password_min_length', 'value' => '8', 'type' => 'integer'],
            ['group' => 'security', 'key' => 'require_uppercase', 'value' => '1', 'type' => 'boolean'],
            ['group' => 'security', 'key' => 'require_number', 'value' => '1', 'type' => 'boolean'],
            ['group' => 'security', 'key' => 'require_special_char', 'value' => '0', 'type' => 'boolean'],

            // Email Settings
            ['group' => 'email', 'key' => 'from_address', 'value' => 'noreply@csu.edu.ph', 'type' => 'string'],
            ['group' => 'email', 'key' => 'from_name', 'value' => 'CSU Student Conduct Office', 'type' => 'string'],
            ['group' => 'email', 'key' => 'send_welcome_email', 'value' => '1', 'type' => 'boolean'],
            ['group' => 'email', 'key' => 'send_incident_notifications', 'value' => '1', 'type' => 'boolean'],

            // Notification Settings
            ['group' => 'notifications', 'key' => 'notify_on_new_incident', 'value' => '1', 'type' => 'boolean'],
            ['group' => 'notifications', 'key' => 'notify_on_case_update', 'value' => '1', 'type' => 'boolean'],
            ['group' => 'notifications', 'key' => 'notify_admin_on_login_failure', 'value' => '1', 'type' => 'boolean'],
            ['group' => 'notifications', 'key' => 'daily_summary_email', 'value' => '0', 'type' => 'boolean'],
        ];

        foreach ($defaults as $setting) {
            Setting::firstOrCreate(
                ['group' => $setting['group'], 'key' => $setting['key']],
                ['value' => $setting['value'], 'type' => $setting['type']]
            );
        }
    }
}
