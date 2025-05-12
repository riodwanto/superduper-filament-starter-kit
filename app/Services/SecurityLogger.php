<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class SecurityLogger
{
    /**
     * Log a suspicious form submission
     *
     * @param string $formType The type of form (e.g., 'contact', 'login')
     * @param string $reason The reason why the submission is suspicious
     * @param array $data Additional data about the submission
     * @param \Illuminate\Http\Request|null $request The request object
     * @return void
     */
    public static function logSuspiciousFormSubmission(string $formType, string $reason, array $data = [], Request $request = null)
    {
        if ($request === null) {
            $request = request();
        }

        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();
        $method = $request->method();
        $url = $request->fullUrl();
        $referer = $request->header('referer');

        // Sanitize any sensitive data
        $sanitizedData = self::sanitizeData($data);

        Log::channel('security')->warning("Suspicious {$formType} form submission: {$reason}", [
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'method' => $method,
            'url' => $url,
            'referer' => $referer,
            'data' => $sanitizedData,
        ]);
    }

    /**
     * Log a rate limit hit
     *
     * @param string $key The rate limiter key
     * @param int $maxAttempts The maximum number of attempts allowed
     * @param int $decaySeconds The decay time in seconds
     * @param \Illuminate\Http\Request|null $request The request object
     * @return void
     */
    public static function logRateLimitHit(string $key, int $maxAttempts, int $decaySeconds, Request $request = null)
    {
        if ($request === null) {
            $request = request();
        }

        Log::channel('security')->warning("Rate limit exceeded", [
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'key' => $key,
            'max_attempts' => $maxAttempts,
            'decay_seconds' => $decaySeconds,
            'url' => $request->fullUrl(),
        ]);
    }

    /**
     * Log a honeypot trigger
     *
     * @param string $fieldName The name of the honeypot field
     * @param string $value The value submitted in the honeypot field
     * @param \Illuminate\Http\Request|null $request The request object
     * @return void
     */
    public static function logHoneypotTrigger(string $fieldName, string $value, Request $request = null)
    {
        if ($request === null) {
            $request = request();
        }

        Log::channel('security')->warning("Honeypot field triggered", [
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'honeypot_field' => $fieldName,
            'submitted_value' => $value,
            'url' => $request->fullUrl(),
        ]);
    }

    /**
     * Log a spam content detection
     *
     * @param string $formType The type of form (e.g., 'contact', 'comment')
     * @param array $matches The spam patterns that were matched
     * @param array $data The form data that triggered the detection
     * @param \Illuminate\Http\Request|null $request The request object
     * @return void
     */
    public static function logSpamContent(string $formType, array $matches, array $data, Request $request = null)
    {
        if ($request === null) {
            $request = request();
        }

        // Sanitize any sensitive data
        $sanitizedData = self::sanitizeData($data);

        Log::channel('security')->warning("Spam content detected in {$formType} form", [
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'matched_patterns' => $matches,
            'data' => $sanitizedData,
            'url' => $request->fullUrl(),
        ]);
    }

    /**
     * Log a security header violation
     *
     * @param string $header The name of the security header
     * @param string $violation The violation details
     * @param \Illuminate\Http\Request|null $request The request object
     * @return void
     */
    public static function logSecurityHeaderViolation(string $header, string $violation, Request $request = null)
    {
        if ($request === null) {
            $request = request();
        }

        Log::channel('security')->warning("Security header violation: {$header}", [
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'header' => $header,
            'violation' => $violation,
            'url' => $request->fullUrl(),
            'referer' => $request->header('referer'),
        ]);
    }

    /**
     * Log suspicious activity pattern
     *
     * @param string $activity Description of the suspicious activity
     * @param array $details Additional details about the activity
     * @param \Illuminate\Http\Request|null $request The request object
     * @return void
     */
    public static function logSuspiciousActivity(string $activity, array $details = [], Request $request = null)
    {
        if ($request === null) {
            $request = request();
        }

        Log::channel('security')->warning("Suspicious activity: {$activity}", array_merge([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
        ], $details));
    }

    /**
     * Sanitize sensitive data before logging
     *
     * @param array $data The data to sanitize
     * @return array The sanitized data
     */
    private static function sanitizeData(array $data): array
    {
        $sensitiveFields = [
            'password', 'password_confirmation', 'current_password',
            'token', 'api_token', 'secret', 'credit_card',
            'card_number', 'cvv', 'ssn', 'social_security',
        ];

        $result = [];

        foreach ($data as $key => $value) {
            // If this is a sensitive field, redact it
            if (in_array(strtolower($key), $sensitiveFields) || stripos($key, 'password') !== false) {
                $result[$key] = '[REDACTED]';
            }
            // If this is an array, recursively sanitize it
            elseif (is_array($value)) {
                $result[$key] = self::sanitizeData($value);
            }
            // Otherwise include the value as is
            else {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
