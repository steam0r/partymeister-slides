<script src="/js/partymeister/dom-to-image2.js"></script>
<script src="/js/partymeister/jquery.ui.rotatable.js"></script>
<script src="/js/partymeister/slidemeister.js"></script>
<script src="/js/partymeister/slidemeister.properties.js"></script>
<script>
    @foreach(config('partymeister-slides-fonts.fonts') as $index => $font)
    slidemeisterProperties.fontFamily.options["{!! $font['family'] !!}"] = "{!! $font['family'] !!}";
    @endforeach
</script>
<script src="/js/medium-editor.min.js"></script>