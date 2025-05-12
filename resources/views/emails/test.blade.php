<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $mailData['title'] }}</title>
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <style>
        @media only screen and (max-width: 600px) {
            .container {
                width: 100% !important;
                padding: 10px !important;
            }
            .content {
                padding: 0 !important;
            }
            .logo img {
                max-width: 200px !important;
                height: auto !important;
            }
        }

        /* Base styles for all themes */
        body {
            margin: 0;
            padding: 0;
            background-color: #f5f7fa;
            color: #333333;
            -webkit-font-smoothing: antialiased;
            font-size: 16px;
            line-height: 1.5;
        }
        .container {
            width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        .header {
            background-color: {{ $theme['primaryColor'] ?? '#2D2B8D' }};
            padding: 24px;
            text-align: center;
        }
        .logo {
            margin-bottom: 0;
        }
        .logo img {
            max-width: 240px;
            height: auto;
        }
        .content {
            padding: 32px 48px;
        }
        .title {
            color: {{ $theme['primaryColor'] ?? '#2D2B8D' }};
            font-size: 24px;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 24px;
            line-height: 1.3;
        }
        .message {
            margin-bottom: 24px;
        }
        .button {
            display: inline-block;
            background-color: {{ $theme['secondaryColor'] ?? '#FFC903' }};
            color: #000000;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            margin: 16px 0;
        }
        .footer {
            background-color: #f5f7fa;
            padding: 24px;
            text-align: center;
            font-size: 14px;
            color: #666666;
        }
        .footer p {
            margin: 0;
        }
        .date {
            color: #999999;
            font-size: 14px;
            margin-bottom: 24px;
        }
        .divider {
            border-top: 1px solid #eeeeee;
            margin: 24px 0;
        }
        .disclaimer {
            font-size: 13px;
            color: #999999;
            margin-top: 24px;
        }

        /* Theme-specific styles */
        @php
            $currentTheme = $theme['theme'] ?? 'default';
        @endphp

        @if($currentTheme == 'minimal')
        /* Minimal theme */
        body {
            font-family: 'Arial', sans-serif;
        }
        .container {
            box-shadow: none;
            border: 1px solid #eeeeee;
        }
        .header {
            background-color: #ffffff;
            padding: 20px;
            border-bottom: 1px solid #eeeeee;
        }
        .logo img {
            max-width: 180px;
        }
        .title {
            font-size: 20px;
            font-weight: 500;
        }
        .content {
            padding: 24px;
        }
        .divider {
            margin: 16px 0;
        }
        .button {
            background-color: #f8f9fa;
            border: 1px solid #dddddd;
            color: #333333;
            font-weight: normal;
            border-radius: 2px;
        }
        .footer {
            background-color: #ffffff;
            border-top: 1px solid #eeeeee;
            padding: 16px;
        }
        @elseif($currentTheme == 'corporate')
        /* Corporate theme */
        body {
            font-family: 'Georgia', serif;
            background-color: #f0f2f5;
        }
        .container {
            border-radius: 0;
        }
        .header {
            padding: 30px;
        }
        .title {
            border-bottom: 2px solid {{ $theme['secondaryColor'] ?? '#FFC903' }};
            padding-bottom: 10px;
            font-family: 'Times New Roman', Times, serif;
        }
        .button {
            border-radius: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 14px;
        }
        .footer {
            background-color: #e0e5eb;
        }
        @elseif($currentTheme == 'modern')
        /* Modern theme */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fafafa;
        }
        .container {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }
        .header {
            padding: 32px;
        }
        .logo img {
            max-width: 180px;
        }
        .content {
            padding: 40px;
        }
        .title {
            font-size: 28px;
            font-weight: 300;
            line-height: 1.2;
        }
        .button {
            padding: 14px 28px;
            border-radius: 30px;
            font-weight: 500;
            text-transform: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        }
        .footer {
            background-color: #f0f0f0;
        }
        @elseif($currentTheme == 'dark')
        /* Dark theme */
        body {
            background-color: #222222;
            color: #eaeaea;
        }
        .container {
            background-color: #333333;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }
        .content {
            color: #eaeaea;
        }
        .title {
            color: {{ $theme['secondaryColor'] ?? '#FFC903' }};
        }
        .date {
            color: #aaaaaa;
        }
        .divider {
            border-color: #444444;
        }
        .button {
            background-color: {{ $theme['secondaryColor'] ?? '#FFC903' }};
            color: #111111;
        }
        .footer {
            background-color: #222222;
            color: #999999;
            border-top: 1px solid #444444;
        }
        .disclaimer {
            color: #777777;
        }
        @endif
    </style>
</head>
<body>
    <div style="display:none; max-height:0; overflow:hidden;">
        {{ $preheader ?? 'This is a test email to verify your email configuration is working properly.' }}
    </div>
    <div style="display:none; max-height:0; overflow:hidden;">&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;</div>

    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td align="center" style="padding: 30px 0;">
                <div class="container">
                    <div class="header">
                        <div class="logo">
                            @if(isset($theme['logo']) && $theme['logo'])
                                <img src="{{ asset('storage/' . $theme['logo']) }}?t={{ time() }}" alt="SuperDuper Starter" />
                            @else
                                <div style="font-size: 28px; color: {{ $currentTheme == 'minimal' ? ($theme['primaryColor'] ?? '#2D2B8D') : 'white' }}; font-weight: bold;">SuperDuper Starter</div>
                            @endif
                        </div>
                    </div>

                    <div class="content">
                        <div class="date">{{ $displayDate ?? now()->format('F j, Y') }}</div>

                        <h1 class="title">{{ $mailData['title'] }}</h1>

                        <div class="message">
                            <p>{{ $mailData['body'] }}</p>
                        </div>

                        <div class="divider"></div>

                        <div>
                            <p>This email serves as confirmation that your mail configuration is working correctly. Below is some example content to demonstrate how your emails will appear:</p>

                            <ul>
                                <li>Your SMTP connection is properly configured</li>
                                <li>Email formatting and styling are applying correctly</li>
                                <li>Images and branding elements are loading</li>
                                <li>The "{{ ucfirst($currentTheme) }}" theme is being displayed correctly</li>
                            </ul>

                            <a href="{{ url('/admin/settings/manage-mail') }}" class="button">Return to Mail Settings</a>
                        </div>

                        <div class="disclaimer">
                            <p>This is an automatically generated email sent at {{ now()->format('H:i:s') }} from your SuperDuper Filament Starter installation.</p>
                        </div>
                    </div>

                    <div class="footer">
                        <p>{{ $footerText ?? ('© ' . date('Y') . ' SuperDuper Starter. All rights reserved.') }}</p>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
