<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use App\Http\Requests\ContactUsRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    // TODO
    // - Multi Language for success & error

    /**
     * Handle the contact form submission.
     *
     * @param ContactUsRequest $request
     * @return RedirectResponse
     */
    public function submit(ContactUsRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            $contact = ContactUs::create([
                'firstname' => $validated['firstname'],
                'lastname' => $validated['lastname'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'company' => $validated['company'] ?? null,
                'employees' => $validated['employees'] ?? null,
                'title' => $validated['title'] ?? null,
                'subject' => $validated['subject'] ?? 'Contact Inquiry',
                'message' => $validated['message'],
                'status' => 'new',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => [
                    'source' => $request->input('source', 'website'),
                    'utm_source' => $request->input('utm_source'),
                    'utm_medium' => $request->input('utm_medium'),
                    'utm_campaign' => $request->input('utm_campaign'),
                    'referrer' => $request->header('referer'),
                    'timestamp' => now()->timestamp,
                ],
            ]);

            DB::commit();

            Log::info('Contact form submitted successfully', [
                'contact_id' => $contact->id,
                'email' => $contact->email
            ]);

            return back()->with('success', 'Your message has been sent successfully. We will get back to you soon!');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Contact form submission error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withInput()->with('error', 'Something went wrong. Please try again later.');
        }
    }
}
