<html>
<head>
    @include('partymeister-slides::layouts.partials.slide_fonts')
    <link href="{{ asset('/css/all.css') }}" rel="stylesheet" type="text/css"/>
    <style type="text/css">
        body {
            background: transparent !important;
        }
    </style>
</head>
<body id="slidemeister-render">
<div id="slidemeister">
    @if ($record->cached_html_preview != '')
        @if ($preview == 'true')
            {!! $record->cached_html_preview !!}
        @else
            {!! $record->cached_html_final !!}
        @endif
    @endif
</div>
@if ($record->cached_html_preview == '')
<script src="/js/app.js"></script>
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
