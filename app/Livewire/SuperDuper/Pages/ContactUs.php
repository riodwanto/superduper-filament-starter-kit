<?php

namespace App\Livewire\SuperDuper\Pages;

use App\Models\ContactUs as ContactUsModel;
use App\Services\SecurityLogger;
use Livewire\Component;
use Illuminate\Support\Facades\RateLimiter;

class ContactUs extends Component
{
    // Form fields
    public $firstname = '';
    public $lastname = '';
    public $email = '';
    public $phone = '';
    public $company = '';
    public $employees = '';
    public $subject = '';
    public $message = '';

    public $company_website = ''; // Honeypot field

    // UI state
    public $success = false;
    public $formSubmitted = false;
    public $submitting = false;

    public $employeeOptions = [
        '1-10' => '1-10 employees',
        '11-50' => '11-50 employees',
        '51-200' => '51-200 employees',
        '201-500' => '201-500 employees',
        '501+' => '501+ employees'
    ];

    protected function rules()
    {
        return [
            // Honeypot field must be empty
            'company_website' => 'prohibited|size:0',

            // Original validation rules
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'employees' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ];
    }

    protected function messages()
    {
        return [
            'company_website.prohibited' => 'Form submission failed.',
            'company_website.size' => 'Form submission failed.',

            // Original messages
            'firstname.required' => 'Please enter your first name.',
            'lastname.required' => 'Please enter your last name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'subject.required' => 'Please enter a subject for your message.',
            'message.required' => 'Please enter your message.',
        ];
    }

    public function mount()
    {
        // Init
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function submit()
    {
        if (!empty($this->company_website)) {
            SecurityLogger::logHoneypotTrigger('company_website', $this->company_website);

            $this->reset(['firstname', 'lastname', 'email', 'phone', 'company', 'employees', 'subject', 'message', 'company_website']);
            $this->success = true;
            $this->formSubmitted = true;
            $this->dispatchBrowserEvent('successMessageShown');
            return;
        }

        $ipAddress = request()->ip();
        $rateLimitKey = 'contact-form:' . $ipAddress;

        // Limit to 5 submissions per hour (3600 seconds)
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            $minutes = ceil($seconds / 60);

            SecurityLogger::logRateLimitHit($rateLimitKey, 5, 3600);

            session()->flash('error', "Too many submissions. Please try again in " .
                ($minutes > 1 ? "$minutes minutes" : "$seconds seconds") . ".");
            return;
        }

        $this->submitting = true;

        $validatedData = $this->validate();

        unset($validatedData['company_website']); // Remove honeypot field from the data

        $validatedData = $this->emptyStringsToNull($validatedData);

        // Check for spam content
        $spamMatches = $this->checkForSpamContent([
            'subject' => $validatedData['subject'] ?? '',
            'message' => $validatedData['message'] ?? '',
        ]);

        if (!empty($spamMatches)) {
            SecurityLogger::logSpamContent('contact', $spamMatches, $validatedData);

            $validatedData['metadata']['spam_detected'] = true;
            $validatedData['metadata']['spam_matches'] = $spamMatches;
        }

        $validatedData['ip_address'] = request()->ip();
        $validatedData['user_agent'] = request()->userAgent();
        $validatedData['status'] = 'new';

        $metadata = [
            'source' => session('utm_source') ?? request()->header('referer') ?? 'direct',
            'url' => request()->fullUrl(),
            'locale' => app()->getLocale(),
        ];

        foreach (['utm_medium', 'utm_campaign', 'utm_content', 'utm_term'] as $param) {
            if (session($param)) {
                $metadata[$param] = session($param);
            }
        }

        $validatedData['metadata'] = $metadata;

        try {
            ContactUsModel::create($validatedData);

            RateLimiter::hit($rateLimitKey, 3600);

            $this->reset(['firstname', 'lastname', 'email', 'phone', 'company', 'employees', 'subject', 'message', 'company_website']);
            $this->success = true;
            $this->formSubmitted = true;

            $this->dispatch('successMessageShown');
        } catch (\Exception $e) {
            SecurityLogger::logSuspiciousActivity('Contact form submission error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            logger()->error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'data' => $validatedData
            ]);

            session()->flash('error', 'Something went wrong. Please try again later.');
        }

        $this->submitting = false;
    }

    /**
     * Check for spam content in form fields
     *
     * @param array $data The data to check
     * @return array An array of matched spam patterns
     */
    protected function checkForSpamContent(array $data): array
    {
        $spamPatterns = [
            'casino|poker|gambling' => 'gambling terms',
            'viagra|cialis|pharmacy|pills' => 'pharmaceutical spam',
            'lottery|winner|prize|congrat' => 'lottery/prize scam',
            'bitcoin|crypto|invest|trading|forex' => 'investment spam',
            'sexy|dating|hot girls|meet singles' => 'dating spam',
            'cheap|discount|sale|buy now|limited time' => 'marketing spam',
            '(?:\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\b){3,}' => 'multiple emails',
            '(?:https?:\/\/[^\s]+){3,}' => 'multiple URLs',
        ];

        $matches = [];
        $content = strtolower(implode(' ', $data));

        foreach ($spamPatterns as $pattern => $description) {
            if (preg_match('/' . $pattern . '/i', $content)) {
                $matches[] = $description;
            }
        }

        return $matches;
    }

    /**
     * Convert empty strings to null values
     *
     * @param array $data
     * @return array
     */
    protected function emptyStringsToNull(array $data): array
    {
        foreach ($data as $key => $value) {
            // Convert empty strings to null
            if (is_string($value) && $value === '') {
                $data[$key] = null;
            }
        }

        return $data;
    }

    // Reset the form
    public function resetForm()
    {
        $this->reset(['firstname', 'lastname', 'email', 'phone', 'company', 'employees', 'subject', 'message', 'company_website']);
        $this->resetValidation();
        $this->formSubmitted = false;
        $this->success = false;
    }

    public function render()
    {
        return view('livewire.superduper.pages.contact-us')
            ->layout('components.superduper.main');
    }
}
