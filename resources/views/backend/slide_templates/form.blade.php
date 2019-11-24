@section('view_styles')
    @include('partymeister-slides::layouts.partials.slide_fonts')
    <style type="text/css">
        .sortable-ghost {
            position: absolute;
            opacity: 0.7;
            top: 50px;
            left: 50px;
            width: 200px;

        .sortable-ghost .file-description {
            display: none;
        }
    </style>
@append
<div class="@boxWrapper box-primary" style="margin-bottom: 0;">
    <div class="@boxHeader">
        TOOLBAR
    </div>
</div>
<div id="slidemeister-wrapper">
    <div id="slidemeister-canvas">
        <div id="slidemeister-canvas-border">
        </div>
        <div id="slidemeister">
            <partymeister-slides-elements id="template-editor" :name="'template-editor'"></partymeister-slides-elements>
        </div>
        <partymeister-slides-dropzone></partymeister-slides-dropzone>
    </div>
</div>
<div class="loader loader-default"
     data-text="&hearts; {{ trans('partymeister-slides::backend/slides.generating') }} &hearts;"></div>
@section ('right-sidebar')
    <ul class="slidemeister-navbar nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#slidemeister-form" role="tab">Properties</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#slidemeister-blocks" role="tab">Mediapool</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="slidemeister-form" role="tabpanel">
            <div class="container">
                <br>
                {!! form_start($form, ['id' => 'slide-template-form']) !!}
                {!! form_row($form->name) !!}
                {!! form_row($form->template_for) !!}
                {!! form_row($form->definitions) !!}
                {!! form_row($form->cached_html_preview) !!}
                {!! form_row($form->cached_html_final) !!}
                {!! form_row($form->image_data) !!}
                {!! form_row($form->submit) !!}
                {!! form_end($form) !!}
                <br>
                <partymeister-slides-controls></partymeister-slides-controls>

            </div>
        </div>
        <div class="tab-pane" id="slidemeister-blocks" role="tabpanel">
            <motor-media-mediapool></motor-media-mediapool>
        </div>
    </div>
@endsection

@section('view_scripts')
    @include('partymeister-slides::layouts.partials.slide_scripts')
    <script>
        $(document).ready(function () {
            $('.slidemeister-save').on('click', function (e) {

                $('.loader').addClass('is-active');

                Vue.prototype.$eventHub.$on('partymeister-slides:receive-definitions', (data) => {
                    if (data.name === 'template-editor') {
                        $('input[name="definitions"]').val(data.definitions);
                        $('input[name="cached_html_preview"]').val($('#slidemeister').html());
                        $('input[name="cached_html_final"]').val($('#slidemeister').html());
                        $('#slide-template-form').submit();
                    }
                });

                Vue.prototype.$eventHub.$emit('partymeister-slides:request-definitions', 'template-editor');
                e.preventDefault();
            });

            if ($('input[name="definitions"]').val() != '') {
                Vue.prototype.$eventHub.$emit('partymeister-slides:load-definitions', {
                    name: 'template-editor',
                    elements: JSON.parse($('input[name="definitions"]').val()),
                    replacements: {}
                });
            }
        });

    </script>
@append
