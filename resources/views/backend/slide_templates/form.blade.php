<div class="loader loader-default"
     data-text="&hearts; Generating slide previews and hiding the ugliness &hearts;"></div>

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
        <div id="vuedropzone" :style="{ zIndex: zIndex }">a
            <draggable v-model="droppedFiles" :options="{group:'files'}" @add="onAdd" class="draggable-container">
            </draggable>
        </div>
    </div>
</div>
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
                {!! form_row($form->png_preview) !!}
                {!! form_row($form->png_final) !!}
                {!! form_row($form->image_data) !!}
                {!! form_row($form->submit) !!}
                {!! form_end($form) !!}
                <br>

                <h6>Blocks</h6>
                <div>
                    <button id="create" class="btn btn-block btn-success btn-sm">Add</button>
                    <button id="clone" class="btn btn-block btn-warning btn-sm">Clone</button>
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
            @include('motor-media::layouts.partials.mediapool', ['header' => false])
        </div>
    </div>
@endsection
@section('view_scripts')
    @include('partymeister-slides::layouts.partials.slide_scripts')
    <script>
        $(document).ready(function () {
            $('.slidemeister-save').on('click', function (e) {

                $('.loader').addClass('is-active');

                var dataToSave = slidemeister.data.save();
                $('input[name="definitions"]').val(JSON.stringify(dataToSave));
                $('input[name="cached_html_preview"]').val($('#slidemeister').html());

                slidemeister.data.export('preview', 1).then(result => {
                    $('input[name="png_preview"]').val(result[2]);

                    slidemeister.data.removePreviewElements();
                    $('input[name="cached_html_final"]').val($('#slidemeister').html());

                    slidemeister.data.export('final', 1).then(result => {
                        $('input[name="png_final"]').val(result[2]);

                        $('#slide-template-form').submit();
                    });

                });

                e.preventDefault();
            });

            slidemeister = $('#slidemeister').slidemeister('#slidemeister-properties', slidemeisterProperties);
            if ($('input[name="definitions"]').val() != '') {
                slidemeister.data.load(JSON.parse($('input[name="definitions"]').val()));
            }

            // Create new element
            $('button#create').on('click', function () {
                slidemeister.element.create();
            });

            // clone new element
            $('button#clone').on('click', function () {
                slidemeister.element.clone();
            });

            // Delete element
            $('button#delete').on('click', function () {
                slidemeister.element.delete();
            });

            // Undo
            $('button#undo').on('click', function () {
                slidemeister.history.back();
            });
            // Redo
            $('button#redo').on('click', function () {
                slidemeister.history.forward();
            });

            let vueSlides = new Vue({
                el: '#vuedropzone',
                data: {
                    droppedFiles: [],
                    zIndex: -1
                },
                components: {
                    draggable,
                },
                methods: {
                    onAdd: function (event) {
                        slidemeister.element.createImage(this.droppedFiles[event.newIndex].file.file_original);
                    },
                    isImage: function (file) {
                        if (file.file.mime_type == 'image/png' || file.file.mime_type == 'image/jpg') {
                            return true;
                        }
                        return false;
                    },
                },
                mounted: function () {
                    vueMediapool.$on('mediapool:drag:start', function() {
                        console.log("drag start");
                        vueSlides.zIndex = 10000;
                    });
                    vueMediapool.$on('mediapool:drag:end', function() {
                        console.log("drag end");
                        vueSlides.zIndex = -1;
                    });
                }
            });

        });

    </script>
@append
