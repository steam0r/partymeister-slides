<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Slidemeister-Web</title>

    <link href="{{ mix('/css/partymeister-slidemeister-web.css') }}" rel="stylesheet" type="text/css"/>
    @include('partymeister-slides::layouts.partials.slide_fonts')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
<div id="app">
    <partymeister-slidemeister-web ref="slidemeisterweb" :jingles='{!! json_encode($jingles) !!}'
                                   :configuration='{!! json_encode($configuration) !!}'
                                   route="{{$route}}"></partymeister-slidemeister-web>
</div>
<script src="{{mix('js/partymeister-slidemeister-web.js')}}"></script>
@yield('view_scripts')
</body>
</html>
