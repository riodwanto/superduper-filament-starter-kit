<?php

namespace Database\Seeders;

use App\Models\ContactUs;
use App\Models\User;
use Illuminate\Database\Seeder;
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

        // Define possible values for status with weighted distribution
        $statusOptions = [
            'new' => 30,         // 30% chance for new
            'read' => 20,        // 20% chance for read
            'pending' => 15,      // 15% chance for pending
            'responded' => 25,    // 25% chance for responded
            'closed' => 10        // 10% chance for closed
        ];

        // Create weighted random status function
        $getRandomStatus = function () use ($statusOptions) {
            $total = array_sum($statusOptions);
            $rand = mt_rand(1, $total);

            $runningTotal = 0;
            foreach ($statusOptions as $status => $weight) {
                $runningTotal += $weight;
                if ($rand <= $runningTotal) {
                    return $status;
                }
            }
            return 'new'; // fallback
        };

        // Get active users to assign as responders
        $users = User::whereNotNull('email_verified_at')->get();

        // If no verified users exist, create a test admin user
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

        // Create a more realistic distribution of dates
        $dateDistribution = [
            'recent' => ['days' => [-7, 0], 'weight' => 40],         // 40% from last week
            'medium' => ['days' => [-30, -7], 'weight' => 35],       // 35% from previous month
            'older' => ['days' => [-90, -30], 'weight' => 25]        // 25% from 1-3 months ago
        ];

        $getRandomDate = function () use ($dateDistribution, $faker) {
            $total = array_sum(array_column($dateDistribution, 'weight'));
            $rand = mt_rand(1, $total);

            $runningTotal = 0;
            foreach ($dateDistribution as $period) {
                $runningTotal += $period['weight'];
                if ($rand <= $runningTotal) {
                    return $faker->dateTimeBetween($period['days'][0] . ' days', $period['days'][1] . ' days');
                }
            }
            return now(); // fallback
        };

        // Define common sources and campaigns for more realistic data
        $sourceOptions = [
            'website' => ['weight' => 50, 'utm_sources' => ['direct', 'organic', 'google', 'bing']],
            'social' => ['weight' => 25, 'utm_sources' => ['facebook', 'twitter', 'linkedin', 'instagram']],
            'email' => ['weight' => 15, 'utm_sources' => ['newsletter', 'drip_campaign', 'promotional']],
            'referral' => ['weight' => 10, 'utm_sources' => ['partner', 'affiliate', 'customer']]
        ];

        $campaignOptions = [
            'spring_promo_2025' => ['start' => '-60 days', 'end' => '-30 days', 'weight' => 20],
            'website_relaunch' => ['start' => '-90 days', 'end' => '-60 days', 'weight' => 15],
            'product_launch' => ['start' => '-30 days', 'end' => 'now', 'weight' => 40],
            'industry_event' => ['start' => '-14 days', 'end' => 'now', 'weight' => 25]
        ];

        // Common inquiries for more realistic subjects and messages
        $inquiryTypes = [
            'general' => [
                'subjects' => [
                    'General inquiry about your services',
                    'Question about your company',
                    'Looking for more information'
                ],
                'templates' => [
                    "I came across your website and I'm interested in learning more about what you offer. Could you please provide additional information about your services?\n\nThank you,\n{name}",
                    "Hello,\n\nI'd like to know more about your company and what you specialize in. Do you have any brochures or additional resources you could share?\n\nBest regards,\n{name}",
                    "Hi there,\n\nI'm researching options for {topic} and would appreciate any information you can provide about your offerings in this area.\n\nThanks,\n{name}"
                ],
                'topics' => ['your products', 'your services', 'your solutions', 'your company']
            ],
            'support' => [
                'subjects' => [
                    'Support needed for your product',
                    'Having trouble with my account',
                    'Technical issue with your service'
                ],
                'templates' => [
                    "I'm experiencing an issue with {problem}. I've tried {solution} but it's still not working. Can someone from your technical team help me resolve this?\n\nThank you,\n{name}",
                    "Hello Support Team,\n\nI need assistance with my account. I'm unable to {problem} and it's preventing me from using your service effectively.\n\nRegards,\n{name}",
                    "Hi,\n\nI've been having trouble with {problem} for the past few days. This is urgent as it's affecting my workflow. Please advise on next steps.\n\nBest,\n{name}"
                ],
                'problems' => ['logging in', 'accessing my data', 'using a specific feature', 'connecting to the service']
            ],
            'sales' => [
                'subjects' => [
                    'Interested in purchasing your product',
                    'Request for pricing information',
                    'Looking for a custom quote'
                ],
                'templates' => [
                    "I'm interested in your {product} for my business. Could you please provide pricing details and information about different plans or packages?\n\nThanks,\n{name}",
                    "Hello Sales Team,\n\nOur company is considering implementing your solution. We have approximately {count} employees and would need {features}. Could you provide a custom quote?\n\nBest regards,\n{name}",
                    "Hi,\n\nI'd like to schedule a demo of your product for my team. We're especially interested in {features}. What are your available times in the next week?\n\nRegards,\n{name}"
                ],
                'products' => ['your software', 'your platform', 'your solution', 'your service'],
                'features' => ['advanced reporting', 'user management', 'API access', 'custom integrations'],
                'counts' => ['25', '50', '100', '250', '500', '1000+']
            ]
        ];

        // Function to get realistic subject and message
        $getInquiryContent = function () use ($faker, $inquiryTypes) {
            // Select inquiry type
            $type = $faker->randomElement(array_keys($inquiryTypes));
            $inquiry = $inquiryTypes[$type];

            // Get subject and message template
            $subject = $faker->randomElement($inquiry['subjects']);
            $messageTemplate = $faker->randomElement($inquiry['templates']);

            // Replace placeholders
            $message = $messageTemplate;
            $message = str_replace('{name}', $faker->firstName . ' ' . $faker->lastName, $message);

            // Handle type-specific replacements
            switch ($type) {
                case 'general':
                    $message = str_replace('{topic}', $faker->randomElement($inquiry['topics']), $message);
                    break;
                case 'support':
                    $message = str_replace('{problem}', $faker->randomElement($inquiry['problems']), $message);
                    $message = str_replace('{solution}', 'restarting and clearing my cache', $message);
                    break;
                case 'sales':
                    $message = str_replace('{product}', $faker->randomElement($inquiry['products']), $message);
                    $message = str_replace('{features}', $faker->randomElement($inquiry['features']), $message);
                    $message = str_replace('{count}', $faker->randomElement($inquiry['counts']), $message);
                    break;
            }

            return [
                'subject' => $subject,
                'message' => $message
            ];
        };

        // Number of records to create
        $recordCount = 100; // Increased from 50 to 100 for better testing

        // Create contact requests
        for ($i = 0; $i < $recordCount; $i++) {
            // Get status with weighted distribution
            $status = $getRandomStatus();

            // Get date with weighted distribution
            $createdAt = $getRandomDate();

            // Get realistic content
            $inquiryContent = $getInquiryContent();
            $subject = $inquiryContent['subject'];
            $message = $inquiryContent['message'];

            // Realistic user agent and IP address
            $userAgent = $faker->userAgent();
            $ipAddress = $faker->ipv4();

            // Generate more realistic marketing data
            $source = $faker->randomElement(array_keys($sourceOptions));
            $sourceData = $sourceOptions[$source];

            $utmSource = mt_rand(1, 100) <= 80 ? $faker->randomElement($sourceData['utm_sources']) : null;
            $utmMedium = $utmSource ? $faker->randomElement(['cpc', 'organic', 'social', 'email', 'referral']) : null;

            // Select campaign based on date
            $utmCampaign = null;
            foreach ($campaignOptions as $campaign => $campaignData) {
                $campaignStart = new Carbon($campaignData['start']);
                $campaignEnd = new Carbon($campaignData['end']);

                if ($createdAt >= $campaignStart && $createdAt <= $campaignEnd) {
                    if (mt_rand(1, 100) <= $campaignData['weight']) {
                        $utmCampaign = $campaign;
                        break;
                    }
                }
            }

            $referrer = $utmSource ? "https://www.{$utmSource}.com" : null;

            // Sometimes add device info to metadata
            $deviceInfo = null;
            if (mt_rand(1, 100) <= 60) {
                $deviceInfo = [
                    'type' => $faker->randomElement(['desktop', 'mobile', 'tablet']),
                    'os' => $faker->randomElement(['Windows', 'MacOS', 'iOS', 'Android', 'Linux']),
                    'browser' => $faker->randomElement(['Chrome', 'Firefox', 'Safari', 'Edge'])
                ];
            }

            $metadata = [
                'source' => $source,
                'utm_source' => $utmSource,
                'utm_medium' => $utmMedium,
                'utm_campaign' => $utmCampaign,
                'referrer' => $referrer,
                'timestamp' => Carbon::parse($createdAt)->timestamp,
                'device' => $deviceInfo,
                'page_view_count' => mt_rand(1, 10),
                'form_completion_time' => mt_rand(30, 300), // seconds
            ];

            $contactUs = ContactUs::seedCreate([
                'firstname' => $faker->firstName(),
                'lastname' => $faker->lastName(),
                'email' => $faker->safeEmail(),
                'phone' => $faker->phoneNumber(),
                'company' => $faker->optional(0.8)->company(),
                'employees' => $faker->optional(0.7)->randomElement($employeeOptions),
                'title' => $faker->jobTitle(),
                'subject' => $subject,
                'message' => $message,
                'status' => $status,
                'created_at' => $createdAt,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'metadata' => $metadata,
            ]);

            // If the status is 'responded' or 'closed', add reply data
            if (in_array($status, ['responded', 'closed'])) {
                // More realistic response times based on message urgency
                $responseTimeHours = 0;

                if (str_contains(strtolower($message), 'urgent')) {
                    $responseTimeHours = rand(1, 8); // 1-8 hours for urgent
                } else {
                    $responseTimeHours = rand(8, 48); // 8-48 hours for non-urgent
                }

                $repliedAt = Carbon::parse($createdAt)->addHours($responseTimeHours);
                $repliedBy = $users->random();

                // Create appropriate response based on inquiry type
                $replyMessage = '';
                if (str_contains(strtolower($subject), 'pricing') || str_contains(strtolower($subject), 'quote')) {
                    $replyMessage = "Thank you for your interest in our products. I've attached our pricing information and would be happy to discuss a custom solution for your needs. Would you be available for a brief call this week to discuss your specific requirements?";
                } elseif (str_contains(strtolower($subject), 'support') || str_contains(strtolower($subject), 'issue') || str_contains(strtolower($subject), 'trouble')) {
                    $replyMessage = "Thank you for reaching out about this issue. Based on your description, I recommend trying the following steps:\n\n1. Clear your browser cache\n2. Ensure you're using a supported browser version\n3. Try logging out and back in\n\nIf you're still experiencing problems after trying these steps, please let me know and I'll escalate this to our technical team.";
                } else {
                    $replyMessage = "Thank you for contacting us. I appreciate your interest in our company.\n\nI'd be happy to provide more information about our services. Based on what you've described, I think our {$faker->word} solution might be a good fit for your needs.\n\nPlease let me know if you have any other questions or if you'd like to schedule a demo.";
                }

                $contactUs->update([
                    'reply_subject' => 'RE: ' . $subject,
                    'reply_message' => $replyMessage,
                    'replied_at' => $repliedAt,
                    'replied_by_user_id' => $repliedBy->id,
                ]);
            }
        }

        $this->command->info("Created {$recordCount} realistic contact requests with weighted statuses and detailed metadata.");
    }
}
