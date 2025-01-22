@php
$template = $pdfData->template;
$layout = $pdfData->layout;
@endphp
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
        style="color: {{ $template->settings['course_title']['color'] }}; 
                font-size: {{ $template->settings['course_title']['font_size'] }}px; 
                font-family: {{
                    match((int)$template->settings['course_title']['font_weight']) {
                        400 => 'TelenorNormal',
                        500 => 'TelenorMedium',
                        default => 'TelenorBold'
                    }
                }};
                top: {{ $template->settings['course_title']['y'] }}px;
                @isset($template->settings['course_title']['x'])
                    left: {{ $template->settings['course_title']['x'] }}px;
                @else
                    width: 100%;
                    text-align: center;
                @endisset">
        {{$pdfData?->course?->title ?? __('No Course Found')}}
    </div>

    <div
        class="placeholder"
        style="color: {{ $template->settings['student_name']['color'] }}; 
                font-size: {{ $template->settings['student_name']['font_size'] }}px; 
                font-family: {{
                    match((int)$template->settings['student_name']['font_weight']) {
                        400 => 'TelenorNormal',
                        500 => 'TelenorMedium',
                        default => 'TelenorBold'
                    }
                }};
                top: {{ $template->settings['student_name']['y'] }}px;
                @isset($template->settings['student_name']['x'])
                    left: {{ $template->settings['student_name']['x'] }}px;
                @else
                    width: 100%;
                    text-align: center;
                @endisset">
        {{$studentName}}
    </div>

    <div
        class="placeholder"
        style="color: {{ $template->settings['date']['color'] }}; 
                font-size: {{ $template->settings['date']['font_size'] }}px; 
                font-family: {{
                    match((int)$template->settings['date']['font_weight']) {
                        400 => 'TelenorNormal',
                        500 => 'TelenorMedium',
                        default => 'TelenorBold'
                    }
                }};
                top: {{ $template->settings['date']['y'] }}px;
                @isset($template->settings['date']['x'])
                    left: {{ $template->settings['date']['x'] }}px;
                @else
                    width: 100%;
                    text-align: center;
                @endisset">
        Date: {{ $pdfData->certificate->created_at->format('Y-m-d') }}
    </div>
</body>

</html>