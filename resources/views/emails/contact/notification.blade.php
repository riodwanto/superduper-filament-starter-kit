<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }
        h1 {
            color: #333333;
            text-align: center;
        }
        .header-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .subject {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .date {
            font-size: 14px;
            color: #777;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #dddddd;
        }
        .label {
            font-weight: bold;
            color: #555555;
            width: 30%;
        }
        .value {
            color: #333333;
        }
        .message-section {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 6px;
        }
        .message-content {
            white-space: pre-line;
            line-height: 1.5;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #777777;
            font-size: 12px;
        }
        .metadata {
            margin-top: 20px;
            padding: 10px;
            background-color: #f1f1f1;
            border-radius: 6px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>New Contact Request</h1>

        <div class="header-details">
            <div class="subject">{{ $contact->subject }}</div>
            <div class="date">Submitted: {{ $contact->created_at->format('F j, Y, g:i a') }}</div>
        </div>

        <p>A new contact request was submitted with the following information:</p>

        <table>
            <tr>
                <td class="label">Name:</td>
                <td class="value">{{ $contact->firstname }} {{ $contact->lastname }}</td>
            </tr>
            <tr>
                <td class="label">Email:</td>
                <td class="value"><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
            </tr>
            @if($contact->phone)
            <tr>
                <td class="label">Phone:</td>
                <td class="value">{{ $contact->phone }}</td>
            </tr>
            @endif
            @if($contact->company)
            <tr>
                <td class="label">Company:</td>
                <td class="value">{{ $contact->company }}</td>
            </tr>
            @endif
            @if($contact->employees)
            <tr>
                <td class="label">Company Size:</td>
                <td class="value">{{ $contact->employees }} employees</td>
            </tr>
            @endif
            @if($contact->title)
            <tr>
                <td class="label">Job Title:</td>
                <td class="value">{{ $contact->title }}</td>
            </tr>
            @endif
        </table>

        <div class="message-section">
            <h3>Message</h3>
            <div class="message-content">{{ $contact->message }}</div>
        </div>

        <div class="metadata">
            <p><strong>Additional Information:</strong></p>
            <p>IP Address: {{ $contact->ip_address ?? 'Not available' }}</p>
            @if(isset($contact->metadata) && is_array($contact->metadata))
                @if(isset($contact->metadata['source']))
                <p>Source: {{ $contact->metadata['source'] }}</p>
                @endif
                @if(isset($contact->metadata['utm_source']))
                <p>Campaign: {{ $contact->metadata['utm_source'] }} / {{ $contact->metadata['utm_medium'] ?? 'N/A' }} / {{ $contact->metadata['utm_campaign'] ?? 'N/A' }}</p>
                @endif
            @endif
        </div>

        <div class="footer">
            <p>This message was sent from the contact form on Your Website.</p>
            <p>Contact ID: {{ $contact->id }}</p>
        </div>
    </div>
</body>
</html>
