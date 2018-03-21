<style type="text/css">
    #playlist-item-container .sortable-ghost {
        opacity: 0.7;
        max-width: 25%;
    }

    #playlist-item-container .sortable-ghost .card-body {
        display: none;
    }

    #playlist-item-container .col-md-3 {
        position: relative;
        padding: 3px;
    }

    #playlist-item-container .item-number, #playlist-item-container .item-delete {
        z-index: 1100;
        text-align: center;
        width: 40px;
        height: 35px;
        font-size: 20px;
        line-height: 35px;
        font-weight: bold;
        background: white;
        border-bottom-right-radius: 10px;
        position: absolute;
        top: 3px;
        Lettermark.png image/png
        left: 3px;
    }

    #playlist-item-container .item-delete {
        background-color: red;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 0;
        right: 3px;
        left: auto;
    }

    .item-delete button {
        color: white;
        background: none;
        border: none;
        padding: 0;
        margin: 0;
    }

    .item-delete button:focus, .item-delete button:active {
        outline: none !important;
        box-shadow: none;
    }

    .image-wrapper {
        width: 100%;
        position: relative;
        padding-top: 56.25%;
        overflow: hidden;
        margin-bottom: 5px;
    }

    .image-wrapper div {
        z-index: 1100;
        position: absolute;
        background-color: white;
        top: 30px;
        width: 40%;
        height: 35px;
        line-height: 35px;
        left: 30%;
        border-radius: 10px;
        text-align: center;
        font-size: 20px;
    }

    #playlist-item-container .image-wrapper .img-fluid {
        z-index: 1000;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
    }

</style>

{!! form_start($form) !!}
<div class="@boxWrapper box-primary">
    <div class="@boxHeader with-border">
        <h3 class="box-title">{{ trans('motor-backend::backend/global.base_info') }}</h3>
    </div>
    <div class="@boxBody">
        {!! form_row($form->name) !!}
        {!! form_row($form->type) !!}
    </div>
    <!-- /.box-body -->
</div>

<div class="@boxWrapper box-primary">
    <div class="@boxHeader with-border">
        <h3 class="box-title">{{ trans('partymeister-slides::backend/playlists.items_info') }}</h3>
    </div>
    <div class="@boxBody">
        <div id="playlist-item-container">
            {!! form_row($form->playlist_items) !!}
            <draggable v-model="droppedFiles" :options="{group:'files'}" @add="onAdd" style="min-height: 100px;"
                       class="row">
                <div v-for="(file, index) in droppedFiles" class="col-md-3" style="border: 1px dotted red;">
                    <div class="item-number">@{{ index+1 }}</div>
                    <div class="item-delete">
                        <button type="button" @click="deleteFile(file)"><i class="fa fa-trash-alt"></i>
                        </button>
                    </div>
                    <div v-if="isImage(file)" class="image-wrapper">
                        <div>{{trans('partymeister-slides::backend/playlists.slide_types.image')}}</div>
                        <img :src="file.file.preview" class="img-fluid">
                    </div>
                    <div v-else> @{{ file.file.file_name }}</div>
                    <div>
                        <div>
                            {{ trans('partymeister-slides::backend/playlists.duration') }} <input type="text" name="duration" v-model="file.duration" size="4"> {{ trans('partymeister-slides::backend/playlists.seconds') }}
                            <input type="checkbox" name="is_advanced_manually" v-model="file.is_advanced_manually"> {{ trans('partymeister-slides::backend/playlists.is_advanced_manually') }}
                        </div>
                        <div>
                            {{ trans('partymeister-slides::backend/playlists.transition') }}
                            <select name="transition_id" v-model="file.transition_identifier">
                                <option v-for="(transition, index) in transitions" :value="transition.identifier">
                                    @{{ transition.name }}
                                </option>
                            </select>
                            <input type="text" name="transition_duration" size="4" v-model="file.transition_duration"> {{ trans('partymeister-slides::backend/playlists.milliseconds') }}
                        </div>
                        <div>
                            {{ trans('partymeister-slides::backend/playlists.midi_note') }} <input type="text" size="2" name="midi_note" v-model="file.midi_note">
                        </div>
                        <div>
                            {{ trans('partymeister-slides::backend/playlists.callback') }}
                            <select name="callback_hash" v-model="file.callback_hash">
                                <option value="">{{ trans('partymeister-slides::backend/callbacks.no_callback') }}</option>
                                <option v-for="(callback, index) in callbacks" :value="callback.hash">
                                    @{{ callback.name }}
                                </option>
                            </select>
                            {{ trans('partymeister-slides::backend/playlists.callback_delay') }}
                            <input type="text" name="callback_delay" v-model="file.callback_delay" size="4"> {{ trans('partymeister-slides::backend/playlists.seconds') }}
                        </div>
                    </div>
                </div>
            </draggable>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="@boxFooter">
        {!! form_row($form->submit) !!}
    </div>
</div>
{!! form_end($form) !!}

@section ('right-sidebar')
    <ul class="slidemeister-navbar nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#partymeister-slides" role="tab">Slides</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#motor-mediapool" role="tab">Mediapool</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="partymeister-slides" role="tabpanel">
            <div class="container" style="overflow:scroll; position: absolute; top: 50px; bottom: 0;">
                <div class="form-group">
                    <label class="control-label">
                        {{ trans('motor-backend::backend/categories.category') }}
                    </label>
                    <select class="form-control" name="category_id" v-model="category_id" @change="refreshFiles">
                        <option value="">{{ trans('motor-backend::backend/categories.all_categories') }}</option>
                        <option v-for="(category, index) in categories" :value="category.id">
                            @{{ category.name }}
                        </option>
                    </select>
                </div>
                <draggable v-model="files" :options="{group:{ name:'files',  pull:'clone', put:false }, sort: false, dragClass: 'sortable-drag', ghostClass: 'sortable-ghost'}" @start="onStart" @end="onEnd">
                    <div v-for="file in files">
                        <div class="card">
                            <img v-if="isImage(file)" class="card-img-top" :src="file.file.preview">
                            <div class="card-body" data-toggle="tooltip" data-placement="top" :title="file.description">
                                <p class="card-text">
                                    @{{ file.name }}<br>
                                    <span class="badge badge-secondary badge-pill">@{{ file.file.mime_type }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </draggable>
            </div>
        </div>
        <div class="tab-pane" id="motor-mediapool" role="tabpanel">
            @include('motor-media::layouts.partials.mediapool', ['header' => false])
        </div>
    </div>
@endsection
@section('view_scripts')
    <script>

        var vueSlides = new Vue({
            el: '#partymeister-slides',
            data: {
                files: [],
                categories: [],
                category_id: '',
            },
            components: {
                draggable,
            },
            methods: {
                onStart: function(e) {
                    this.$emit('mediapool:drag:start', true);
                },
                onEnd: function(e) {
                    this.$emit('mediapool:drag:end', true);
                },
                refreshFiles: function() {
                    axios.get('{{route('ajax.slides.index')}}?category_id='+this.category_id).then(function(response) {
                        vueSlides.files = response.data.data;
                    });
                },
                isImage: function(file) {
                    if (file.file.mime_type == 'image/png' || file.file.mime_type == 'image/jpg') {
                        return true;
                    }
                    return false;
                }
            },
            mounted: function () {

                axios.get('{{route('ajax.categories.index')}}?scope=slides').then(function(response) {
                    vueSlides.categories = response.data.data;
                });
                axios.get('{{route('ajax.slides.index')}}').then(function(response) {
                    vueSlides.files = response.data.data;
                });
            }
        });
    </script>
@append
@section('view_scripts')
    <script>

        $('.playlist-submit').on('click', function (e) {
            e.preventDefault();
            $('#playlist-items').val(JSON.stringify(vuePlaylists.droppedFiles));
            $(this).closest('form').submit();
        });

        let vuePlaylists = new Vue({
            el: '#playlist-item-container',
            data: {
                droppedFiles: [],
                transitions: [],
                callbacks: []
            },
            components: {
                draggable,
            },
            methods: {
                onAdd: function (event) {
                    Vue.set(this.droppedFiles[event.newIndex], 'duration', 20);
                    Vue.set(this.droppedFiles[event.newIndex], 'midi_note', 0);
                    Vue.set(this.droppedFiles[event.newIndex], 'transition_identifier', 255);
                    Vue.set(this.droppedFiles[event.newIndex], 'transition_duration', 2000);
                    Vue.set(this.droppedFiles[event.newIndex], 'callback_hash', '');
                    Vue.set(this.droppedFiles[event.newIndex], 'callback_delay', 20);
                    Vue.set(this.droppedFiles[event.newIndex], 'is_advanced_manually', true);
                },
                isImage: function (file) {
                    if (file.file.mime_type == 'image/png' || file.file.mime_type == 'image/jpg') {
                        return true;
                    }
                    return false;
                },
                deleteFile: function (file) {
                    this.droppedFiles.splice(this.droppedFiles.indexOf(file), 1);
                }
            },
            mounted: function () {
                @if (isset($playlistItems))
                    this.droppedFiles = {!! $playlistItems !!};
                @endif


                axios.get('{{route('ajax.transitions.index')}}').then(function (response) {
                    vuePlaylists.transitions = response.data.data;
                    // vueMediapool.$emit('test', {data: 'lol'});
                });
            }
        });
    </script>
@append
