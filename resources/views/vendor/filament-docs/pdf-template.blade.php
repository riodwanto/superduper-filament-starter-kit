<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }} - Complete Documentation</title>
    <style>
        @page { 
            size: A4; 
            margin: 2cm; 
        }
        
        body { 
            font-family: 'DejaVu Sans', sans-serif; 
            line-height: 1.6; 
            margin: 0; 
            padding: 0; 
            font-size: 11pt;
            color: #333;
        }
        
        .title-page {
            text-align: center;
            page-break-after: always;
            padding: 100px 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 80vh;
        }
        
        .title-page h1 {
            font-size: 3rem;
            color: #1f2937;
            margin-bottom: 1rem;
        }
        
        .title-page p {
            font-size: 1.2rem;
            color: #6b7280;
            margin: 0.5rem 0;
        }
        
        h1, h2, h3, h4, h5, h6 { 
            color: #1f2937; 
            margin-top: 24px; 
            margin-bottom: 12px; 
            page-break-after: avoid;
        }
        
        h1 { 
            font-size: 1.8rem; 
            border-bottom: 2px solid #e5e7eb; 
            padding-bottom: 8px; 
        }
        
        h2 { font-size: 1.4rem; }
        h3 { font-size: 1.2rem; }
        h4 { font-size: 1.1rem; }
        
        p { 
            margin-bottom: 12px; 
            text-align: justify;
        }
        
        ul, ol { 
            margin-bottom: 12px; 
            padding-left: 24px; 
        }
        
        li { 
            margin-bottom: 4px; 
        }
        
        code { 
            background-color: #f3f4f6; 
            padding: 2px 4px; 
            border-radius: 4px; 
            font-family: 'DejaVu Sans Mono', monospace; 
            font-size: 0.9em;
        }
        
        pre { 
            background-color: #f3f4f6; 
            padding: 12px; 
            border-radius: 6px; 
            font-family: 'DejaVu Sans Mono', monospace;
            font-size: 0.85em;
            page-break-inside: avoid;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        
        blockquote { 
            border-left: 4px solid #d1d5db; 
            padding-left: 16px; 
            margin: 16px 0; 
            font-style: italic; 
        }
        
        table { 
            border-collapse: collapse; 
            width: 100%; 
            margin-bottom: 16px;
            page-break-inside: avoid;
        }
        
        th, td { 
            border: 1px solid #d1d5db; 
            padding: 8px 12px; 
            text-align: left; 
            font-size: 0.9em;
        }
        
        th { 
            background-color: #f9fafb; 
            font-weight: 600; 
        }
        
        img {
            max-width: 100%;
            height: auto;
            page-break-inside: avoid;
        }
        
        .section {
            page-break-inside: avoid;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="title-page">
        <h1>{{ $title }}</h1>
        <p>Complete Documentation</p>
        <p>Generated on {{ $generatedDate }}</p>
    </div>
    
    <div class="content">
        {!! $content !!}
    </div>
    
    <div class="footer">
        <p>{{ $title }} - Generated on {{ $generatedDate }}</p>
    </div>
</body>
</html>
