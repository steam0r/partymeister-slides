<html>
<head>
    @include('partymeister-slides::layouts.partials.slide_fonts')
    <link href="{{env('SCREENS_URL')}}{{ mix('/css/motor-backend.css') }}" rel="stylesheet" type="text/css"/>
    <style type="text/css">
        body {
            background: transparent !important;
        }

        .clock {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translateX(-50%) translateY(-50%);
            color: #17D4FE;
            font-size: 60px;
            font-family: Orbitron;
            letter-spacing: 7px;



        }

    </style>
</head>
<body id="slidemeister-render" scroll="no" style="overflow: hidden">
<div id="slidemeister">
    @if ($record->cached_html_preview != '')
        @if ($preview == 'true')
            {!! $record->cached_html_preview !!}
        @else
            <div id="MyClockDisplay" class="clock" onload="showTime()"></div>
            {!! str_replace('/media/', env('SCREENS_URL').'/media/', $record->cached_html_final) !!}
        @endif
    @endif
</div>
<script>
    function showTime(){
        var date = new Date();
        var h = date.getHours(); // 0 - 23
        var m = date.getMinutes(); // 0 - 59
        var s = date.getSeconds(); // 0 - 59
        var session = "AM";

        if(h == 0){
            h = 12;
        }

        if(h > 12){
            h = h - 12;
            session = "PM";
        }

        h = (h < 10) ? "0" + h : h;
        m = (m < 10) ? "0" + m : m;
        s = (s < 10) ? "0" + s : s;

        var time = h + ":" + m + ":" + s + " " + session;
        document.getElementById("MyClockDisplay").innerText = time;
        document.getElementById("MyClockDisplay").textContent = time;

        setTimeout(showTime, 1000);

    }

    showTime();
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
