<html>
<head>
    @include('partymeister-slides::layouts.partials.slide_fonts')
    @if (strpos($_SERVER['HTTP_USER_AGENT'], 'AltonaHTTPClient') > 0)
        <link href="{{env('SCREENS_URL')}}{{ mix('/css/motor-backend.css') }}" rel="stylesheet" type="text/css"/>
    @else
        <link href="{{env('APP_URL')}}{{ mix('/css/motor-backend.css') }}" rel="stylesheet" type="text/css"/>
    @endif

    <style type="text/css">
        body {
            background: transparent !important;
        }
    </style>
</head>
<body id="slidemeister-render" scroll="no" style="overflow: hidden">
<div id="slidemeister">
    @if ($record->cached_html_preview != '')
        @if ($preview == 'true')
            {!! $record->cached_html_preview !!}
        @else
            {!! str_replace('/media/', env('SCREENS_URL').'/media/', $record->cached_html_final) !!}
        @endif
    @endif
</div>
<script>
    window.addEventListener('load', (event) => {
        console.log('page is fully loaded');
    });
</script>
@if ($record->cached_html_preview == '')
    <script src="{{mix('js/motor-backend.js')}}"></script>
    @include('partymeister-slides::layouts.partials.slide_scripts')
    <script>
        $(window).bind("load", function () {
            slidemeister = $('#slidemeister').slidemeister('#slidemeister-properties', slidemeisterProperties);
            slidemeister.data.load({!! $record->definitions !!}, {!! $placeholderData !!}, false, {{$preview}});
        });
    </script>
@endif
</body>
</html>
