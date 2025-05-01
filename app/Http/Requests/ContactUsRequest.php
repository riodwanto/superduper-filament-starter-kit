<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactUsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Everyone can submit the contact form
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string', 'max:100'],
            'lastname' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc,dns', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'company' => ['nullable', 'string', 'max:150'],
            'employees' => [
                'nullable',
                'string',
                Rule::in(['1-10', '11-50', '51-200', '201-500', '501-1000', '1000+'])
            ],
            'title' => ['nullable', 'string', 'max:150'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'firstname.required' => 'Please provide your first name.',
            'lastname.required' => 'Please provide your last name.',
            'email.required' => 'Please provide your email address.',
            'email.email' => 'Please provide a valid email address.',
            'email.dns' => 'The email domain appears to be invalid.',
            'subject.required' => 'Please provide a subject for your message.',
            'message.required' => 'Please include a message.',
            'message.min' => 'Your message should be at least 10 characters long.',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // If we have title but no subject (backward compatibility), use title as subject
        if ($this->has('title') && !$this->has('subject') && $this->title) {
            $this->merge([
                'subject' => $this->title,
            ]);
        }
    }
}
