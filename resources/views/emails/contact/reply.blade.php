<x-mail::message>
# Hello {{ $contactUs->name }},

Thank you for reaching out to us. Here's our response to your inquiry:

<x-mail::panel>
{!! $contactUs->reply_message !!}
</x-mail::panel>

Your original message:
<x-mail::panel>
**Subject:** {{ $contactUs->subject }}

{{ $contactUs->message }}
</x-mail::panel>

If you have any further questions, please don't hesitate to contact us again.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
