<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau message</title>
    <style>
        body {
            font-family: 'Google Sans', Roboto, Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
            color: #202124;
            font-size: 14px;
            line-height: 1.4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #dadce0;
            border-radius: 8px;
        }
        .header {
            padding: 24px 24px 0 24px;
            border-bottom: 1px solid #e8eaed;
            margin-bottom: 24px;
        }
        .logo {
            color: #ff7000;
            font-size: 22px;
            font-weight: 400;
            margin-bottom: 8px;
        }
        .subject-line {
            color: #5f6368;
            font-size: 14px;
            margin-bottom: 24px;
        }
        .content {
            padding: 0 24px;
        }
        .row {
            margin-bottom: 16px;
        }
        .label {
            color: #5f6368;
            font-size: 12px;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .value {
            color: #202124;
            font-size: 14px;
        }
        .message-content {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 16px;
            margin: 20px 0;
            color: #202124;
            white-space: pre-wrap;
        }
        .metadata {
            margin-top: 32px;
            padding-top: 16px;
            border-top: 1px solid #e8eaed;
            font-size: 12px;
            color: #5f6368;
        }
        .metadata-row {
            margin-bottom: 4px;
        }
        .footer {
            margin-top: 32px;
            padding: 16px 24px;
            font-size: 11px;
            color: #5f6368;
            text-align: center;
        }
        .type-indicator {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
            background-color: #fff4ed;
            color: #ea580c;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">Faceit Scope</div>
            <div class="subject-line">{{ $contactData['subject'] }}</div>
        </div>
        
        <div class="content">
            <div class="row">
                <div class="label">De</div>
                <div class="value">{{ $contactData['email'] }}@if($contactData['pseudo'] && $contactData['pseudo'] !== 'Non fourni') ({{ $contactData['pseudo'] }})@endif</div>
            </div>

            <div class="row">
                <div class="label">Type</div>
                <div class="value">
                    <span class="type-indicator">
                        @switch($contactData['type'])
                            @case('bug') Problème technique
                            @break
                            @case('suggestion') Suggestion
                            @break
                            @case('question') Question
                            @break
                            @case('feedback') Commentaire
                            @break
                            @default Autre
                        @endswitch
                    </span>
                </div>
            </div>

            <div class="message-content">{{ $contactData['message'] }}</div>

            <div class="metadata">
                <div class="metadata-row">ID: {{ $contactData['ticket_id'] }}</div>
                <div class="metadata-row">{{ $contactData['submitted_at']->format('d/m/Y à H:i') }}</div>
                <div class="metadata-row">{{ $contactData['user_ip'] }}</div>
            </div>
        </div>

        <div class="footer">
            Ce message a été envoyé via le formulaire de contact de Faceit Scope
        </div>
    </div>
</body>
</html>