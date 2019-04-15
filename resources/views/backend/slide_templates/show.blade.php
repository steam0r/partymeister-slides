<html>
<head>
    @include('partymeister-slides::layouts.partials.slide_fonts')
    <link href="{{env('SCREENS_URL')}}{{ mix('/css/motor-backend.css') }}" rel="stylesheet" type="text/css"/>
    <style type="text/css">
        body {
            background: transparent !important;
        }
    </style>
</head>
<body id="slidemeister-render" scroll="no" style="overflow: hidden" onload="startTime()">
<div id="slidemeister">
    @if ($record->cached_html_preview != '')
        @if ($preview == 'true')
            {!! $record->cached_html_preview !!}
        @else
            <div id="txt" style="position: absolute; left: 50; top: 50;"></div>
            {!! str_replace('/media/', env('SCREENS_URL').'/media/', $record->cached_html_final) !!}
        @endif
    @endif
</div>
<script>
    function startTime() {
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('txt').innerHTML =
            h + ":" + m + ":" + s;
        var t = setTimeout(startTime, 500);
    }
    function checkTime(i) {
        if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
        return i;
    }
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
