@section('main')
@section('view_styles')
    @include('partymeister-slides::layouts.partials.slide_fonts')
    <style type="text/css">
        .sortable-ghost {
            position: absolute;
            opacity: 0.7;
            top: 50px;
            left: 50px;
            width: 200px;
            /*top: 30%;*/
            /*left: 40%;*/
            /*max-width: 20%;*/
        }

        .sortable-ghost .file-description {
            display: none;
        }
    </style>
@append
@section('main-content')
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
        </div>
        <partymeister-slides-dropzone></partymeister-slides-dropzone>
    </div>
</div>
<div class="loader loader-default"
     data-text="&hearts; Generating slide previews and hiding the ugliness &hearts;"></div>
{{--<img id="preview">--}}
@endsection
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
                {!! form_start($form, ['id' => 'slide-form']) !!}
                {!! form_row($form->name) !!}
                {!! form_row($form->category_id) !!}
                {!! form_row($form->slide_type) !!}
                {!! form_row($form->definitions) !!}
                {!! form_row($form->cached_html_preview) !!}
                {!! form_row($form->cached_html_final) !!}
                {!! form_row($form->png_preview) !!}
                {!! form_row($form->png_final) !!}
                {!! form_row($form->image_data) !!}
                {!! form_row($form->submit) !!}
                {!! form_end($form, false) !!}
                <br>

                <h6>Blocks</h6>
                <div>
                    <button id="delete" class="btn btn-block btn-danger btn-sm">Delete</button>
                </div>
                <hr>

                <h6>Block properties</h6>
                <div id="slidemeister-properties">
                </div>
                <hr>

                <h6>Layers</h6>
                <div id="slidemeister-layers-container">

                </div>
            </div>
        </div>
        <div class="tab-pane" id="slidemeister-blocks" role="tabpanel">
            <motor-media-mediapool></motor-media-mediapool>
            {{--@include('motor-media::layouts.partials.mediapool', ['header' => false])--}}
        </div>
    </div>
@endsection

@section('view_scripts')
    @include('partymeister-slides::layouts.partials.slide_scripts')
    <script>
        $(document).ready(function () {
            $('.slidemeister-save').on('click', function (e) {
                e.preventDefault();

                $('.loader').addClass('is-active');

                let dataToSave = slidemeister.data.save();
                $('input[name="definitions"]').val(JSON.stringify(dataToSave));
                $('input[name="cached_html_preview"]').val($('#slidemeister').html());

                slidemeister.data.export('preview', 1).then(result => {
                    $('input[name="png_preview"]').val(result[2]);

                    slidemeister.data.removePreviewElements();
                    $('input[name="cached_html_final"]').val($('#slidemeister').html());

                    $('#slide-form').submit();
                    // slidemeister.data.export('final', 1).then(result => {
                    //     $('input[name="png_final"]').val(result[2]);
                    //     // $('#preview').prop('src', result[2]);
                    //
                    //
                    // });

                });
            });

            slidemeisterProperties.opacity.visible = false;
            slidemeisterProperties.editable.visible = false;
            slidemeisterProperties.visibility.visible = false;
            slidemeisterProperties.locked.visible = false;
            slidemeisterProperties.backgroundColor.visible = false;
            slidemeisterProperties.snapping.visible = false;
            slidemeisterProperties.prettyname.visible = false;
            slidemeisterProperties.placeholder.visible = false;

            slidemeister = $('#slidemeister').slidemeister('#slidemeister-properties', slidemeisterProperties);
            if ($('input[name="definitions"]').val() != '') {
                slidemeister.data.load(JSON.parse($('input[name="definitions"]').val()));
            }

            // Delete element
            $('button#delete').on('click', function () {
                slidemeister.element.delete();
            });

            Vue.prototype.$eventHub.$on('partymeister-slides:image-dropped', (image) => {
                slidemeister.element.createImage(image);
            });

            // Undo
            $('button#undo').on('click', function () {
                slidemeister.history.back();
            });
            // Redo
            $('button#redo').on('click', function () {
                slidemeister.history.forward();
            });
        });

    </script>
@append
