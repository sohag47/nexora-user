<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserAdditionalInfo>
 */
class UserAdditionalInfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Foreign Key
            'user_id' => User::factory(),

            // Contact & Address Info
            'additional_phone_no' => fake()->phoneNumber(),
            'present_address' => fake()->address(),
            'permanent_address' => fake()->address(),
            'mailing_address' => fake()->address(),
            'office_address' => fake()->company() . ', ' . fake()->address(),

            // Emergency Contact
            'emergency_contact_name' => fake()->name(),
            'emergency_contact_no' => fake()->phoneNumber(),
            'emergency_contact_relation' => fake()->randomElement(['Brother', 'Sister', 'Parent', 'Spouse', 'Friend']),

            // Personal Info
            'blood_group' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']),
            'nationality' => fake()->country(),
            'religion' => fake()->randomElement(['Islam', 'Hinduism', 'Christianity', 'Buddhism', 'Other']),
            'marital_status' => fake()->randomElement(['Single', 'Married', 'Divorced', 'Widowed']),
            'disabilities' => fake()->randomElement(['None', 'None', 'None', 'Visually Impaired']), // Weighted to 'None'

            // Family Info
            'fathers_name' => fake()->name('male'),
            'fathers_nid_no' => fake()->numerify('##########'),
            'mothers_name' => fake()->name('female'),
            'mothers_nid_no' => fake()->numerify('##########'),
            'spouse_name' => fake()->name(),

            // Documents & Identifications
            'nid_no' => fake()->numerify('##########'),
            'driving_license_no' => fake()->bothify('DL-########'),
            'passport_no' => fake()->bothify('??#######'),

            // Professional Info
            'profession' => fake()->jobTitle(),
            'grade' => fake()->randomElement(['Grade A', 'Grade B', 'Grade C']),
            'employee_code' => fake()->unique()->bothify('EMP-####'),
            'joining_date' => fake()->dateTimeBetween('-5 years', 'now'),
            'expire_date' => fake()->dateTimeBetween('now', '+5 years'),
            'comment' => fake()->sentence(),

            // Nominee Info
            'nominee_name' => fake()->name(),
            'nominee_nid' => fake()->numerify('##########'),
            'nominee_phone_no' => fake()->phoneNumber(),
            'nominee_address' => fake()->address(),

            // Academic/Institution Info
            'institution_name' => fake()->randomElement(['Dhaka University', 'BUET', 'NSU', 'BRAC']),
            'student_id' => fake()->bothify('STU-####'),

            // Cards & Hardware Info
            'hf_card_no' => fake()->bothify('HF-########'),
            'uhf_card_no' => fake()->bothify('UHF-########'),
            // 'reporting_head_id' => User::factory(), // Uncomment if this relates to another user
            'validity_start_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'validity_end_date' => fake()->dateTimeBetween('now', '+1 year'),
            'card_printed_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'is_card_printed' => fake()->boolean(80), // 80% chance it's true

            // The following fields are left NULL by default for performance:
            // email_app_password, spouse_nid_no, signature, all *_image fields,
            // all *_photo fields, seal, left_irish, right_irish, finger_binary, etc.
        ];
    }
}
