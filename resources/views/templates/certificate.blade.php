<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        body,
        html {
            margin: 1px;
            padding: 0;
            background: #f8f9fa;
        }

        @font-face {
            font-family: 'TelenorBold';
            src: url('{{ public_path(' fonts/TelenorEvolution-Bold.otf') }}') format('truetype');
        }

        @font-face {
            font-family: 'TelenorMedium';
            src: url('{{ public_path(' fonts/TelenorEvolution-Medium.otf') }}') format('truetype');
        }

        @font-face {
            font-family: 'TelenorNormal';
            src: url('{{ public_path(' fonts/TelenorEvolution-Normal.otf') }}') format('truetype');
        }

        .bg {
            position: relative;
            background: url('data:image/png;base64, {{ $pdfData->base64Image }}') no-repeat center center/cover;
        }

        .placeholder {
            position: absolute;
        }
    </style>
</head>

<body class="bg">
    <div
        class="placeholder"
        style="color: {{ $pdfData->settings['course_title']['color'] }}; 
                font-size: {{ $pdfData->settings['course_title']['font_size'] }}px; 
                font-family: {{
                    match((int)$pdfData->settings['course_title']['font_weight']) {
                        400 => 'TelenorNormal',
                        500 => 'TelenorMedium',
                        default => 'TelenorBold'
                    }
                }};
                top: {{ $pdfData->settings['course_title']['y'] }}px;
                @isset($pdfData->settings['course_title']['x'])
                    left: {{ $pdfData->settings['course_title']['x'] }}px;
                @else
                    width: 100%;
                    text-align: center;
                @endisset">
        {{$pdfData?->certificate?->title ?? __('No Course Found')}}
    </div>

    <div
        class="placeholder"
        style="color: {{ $pdfData->settings['student_name']['color'] }}; 
                font-size: {{ $pdfData->settings['student_name']['font_size'] }}px; 
                font-family: {{
                    match((int)$pdfData->settings['student_name']['font_weight']) {
                        400 => 'TelenorNormal',
                        500 => 'TelenorMedium',
                        default => 'TelenorBold'
                    }
                }};
                top: {{ $pdfData->settings['student_name']['y'] }}px;
                @isset($pdfData->settings['student_name']['x'])
                    left: {{ $pdfData->settings['student_name']['x'] }}px;
                @else
                    width: 100%;
                    text-align: center;
                @endisset">
        {{$studentName}}
    </div>

    <div
        class="placeholder"
        style="color: {{ $pdfData->settings['date']['color'] }}; 
                font-size: {{ $pdfData->settings['date']['font_size'] }}px; 
                font-family: {{
                    match((int)$pdfData->settings['date']['font_weight']) {
                        400 => 'TelenorNormal',
                        500 => 'TelenorMedium',
                        default => 'TelenorBold'
                    }
                }};
                top: {{ $pdfData->settings['date']['y'] }}px;
                @isset($pdfData->settings['date']['x'])
                    left: {{ $pdfData->settings['date']['x'] }}px;
                @else
                    width: 100%;
                    text-align: center;
                @endisset">
        Date: {{ $pdfData->certificate->created_at->format('Y-m-d') }}
    </div>
</body>

</html>