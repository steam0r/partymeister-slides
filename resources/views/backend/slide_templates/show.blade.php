<html>
<head>
    @include('partymeister-slides::layouts.partials.slide_fonts')
    <link href="{{ asset('/css/all.css') }}" rel="stylesheet" type="text/css"/>
</head>
<body id="slidemeister-render">
<div id="slidemeister">
</div>
<script src="/js/app.js"></script>
@include('partymeister-slides::layouts.partials.slide_scripts')
<script>
    $(window).bind("load", function () {
        slidemeister = $('#slidemeister').slidemeister('#slidemeister-properties', slidemeisterProperties);
        slidemeister.data.load({!! $record->definitions !!}, {!! $placeholderData !!}, false, {{$preview}});
    });
</script>
</body>
</html>
