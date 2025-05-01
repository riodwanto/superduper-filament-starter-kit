<?php

namespace Database\Seeders;

use App\Models\ContactUs;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ContactUsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        // Define possible values for employees field
        $employeeOptions = [
            '1-10',
            '11-50',
            '51-200',
            '201-500',
            '501-1000',
            '1000+'
        ];

        // Define possible values for status
        $statusOptions = ['new', 'read', 'pending', 'responded', 'closed'];

        // Get some users to assign as responders
        $users = User::all();

        // If no users exist, create a test user
        if ($users->isEmpty()) {
            $user = User::create([
                'username' => 'admin',
                'firstname' => 'Admin',
                'lastname' => 'User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
            $users = collect([$user]);
        }

        // Create 50 contact requests
        for ($i = 0; $i < 50; $i++) {
            $status = $faker->randomElement($statusOptions);
            $createdAt = $faker->dateTimeBetween('-3 months', 'now');
            $jobTitle = $faker->jobTitle();
            $subject = $faker->sentence(rand(4, 8));
            $userAgent = $faker->userAgent();
            $ipAddress = $faker->ipv4();

            // Create UTM parameters and metadata
            $source = $faker->randomElement(['website', 'email', 'social', 'referral', 'organic']);
            $utmSource = $faker->randomElement(['google', 'facebook', 'twitter', 'linkedin', 'email', null]);
            $utmMedium = $utmSource ? $faker->randomElement(['cpc', 'organic', 'social', 'email', null]) : null;
            $utmCampaign = $utmMedium ? $faker->randomElement(['spring_sale', 'product_launch', 'newsletter', null]) : null;
            $referrer = $faker->randomElement([$faker->url(), null]);

            $metadata = [
                'source' => $source,
                'utm_source' => $utmSource,
                'utm_medium' => $utmMedium,
                'utm_campaign' => $utmCampaign,
                'referrer' => $referrer,
                'timestamp' => Carbon::parse($createdAt)->timestamp,
            ];

            $contactUs = ContactUs::create([
                'firstname' => $faker->firstName(),
                'lastname' => $faker->lastName(),
                'email' => $faker->safeEmail(),
                'phone' => $faker->phoneNumber(),
                'company' => $faker->optional(0.8)->company(),
                'employees' => $faker->optional(0.7)->randomElement($employeeOptions),
                'title' => $jobTitle,
                'subject' => $subject,
                'message' => $faker->paragraphs(rand(2, 5), true),
                'status' => $status,
                'created_at' => $createdAt,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'metadata' => $metadata,
            ]);

            // If the status is 'responded' or 'closed', add reply data
            if (in_array($status, ['responded', 'closed'])) {
                $repliedAt = Carbon::parse($createdAt)->addDays(rand(0, 5))->addHours(rand(1, 24));
                $repliedBy = $users->random();

                $contactUs->update([
                    'reply_subject' => 'RE: ' . $subject,
                    'reply_message' => $faker->paragraphs(rand(1, 3), true),
                    'replied_at' => $repliedAt,
                    'replied_by_user_id' => $repliedBy->id,
                ]);
            }
        }

        $this->command->info('Created 50 contact requests with various statuses and metadata.');
    }
}
