<template>
    <div class="tab-pane active" id="partymeister-slides" role="tabpanel">
        <div class="container" style="overflow:scroll; position: absolute; top: 50px; bottom: 0;">
            <div class="form-group">
                <label class="control-label">
                    {{ $t('motor-backend.backend.categories.category') }}
                </label>
                <select class="form-control" name="category_id" v-model="category_id" @change="refreshFiles">
                    <option value="">{{ $t('motor-backend.backend.categories.all_categories') }}</option>
                    <option v-for="(category, index) in categories" :value="category.id">
                        {{ category.name }}
                    </option>
                </select>
            </div>
            <button type="button" class="btn btn-sm btn-primary float-right" @click="next" v-if="pagination && pagination.total_pages > 1 && pagination.current_page < pagination.total_pages"> >> </button>
            <button type="button" class="btn btn-sm btn-primary float-left" @click="previous" v-if="pagination && pagination.total_pages > 1 && (pagination.current_page >= pagination.total_pages || (pagination.current_page > 1 && pagination.current_page < pagination.total_pages))"> << </button>
            <div class="clearfix mb-2"></div>
            <draggable v-model="files" :options="{group:{ name:'files',  pull:'clone', put:false }, sort: false, dragClass: 'sortable-drag', ghostClass: 'sortable-ghost'}" @start="onStart" @end="onEnd">
                <div v-for="file in files">
                    <div class="card">
                        <img v-if="file.file.is_generating" class="card-img-top" :src="previewImage">
                        <img v-if="!file.file.is_generating && isImage(file)" class="card-img-top" :src="file.file.preview">
                        <div class="card-body" data-toggle="tooltip" data-placement="top" :title="file.description">
                            <p class="card-text">
                                {{ file.name }}<br>
                                <span class="badge badge-secondary badge-pill">{{ file.file.mime_type }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </draggable>
        </div>
    </div>
</template>

<script>
    import draggable from 'vuedraggable';
    import route from 'ziggy-route';
    import {Ziggy} from 'ziggy';

    window.Ziggy = Ziggy;

    Vue.mixin({
        methods: {
            route: route
        }
    });


    export default {
        name: 'partymeister-slides-mediapool',
        props: ['previewImage'],
        data: function() {
            return {
                files: [],
                categories: [],
                category_id: '',
                pagination: false
            };
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
                axios.get(route('ajax.slides.index')+'?sortable_field=created_at&sortable_direction=DESC&category_id='+this.category_id).then((response) => {
                    this.files = response.data.data;
                    this.pagination = response.data.meta.pagination;
                });
            },
            next: function() {
                axios.get(route('ajax.slides.index')+'?sortable_field=created_at&sortable_direction=DESC&category_id='+this.category_id+'&page='+(this.pagination.current_page+1)).then((response) => {
                    this.files = response.data.data;
                    this.pagination = response.data.meta.pagination;
                });
            },
            previous: function() {
                axios.get(route('ajax.slides.index')+'?sortable_field=created_at&sortable_direction=DESC&category_id='+this.category_id+'&page='+(this.pagination.current_page-1)).then((response) => {
                    this.files = response.data.data;
                    this.pagination = response.data.meta.pagination;
                });
            },
            isImage: function(file) {
                if (file.file.mime_type == 'image/png' || file.file.mime_type == 'image/jpg' || file.file.mime_type == 'image/jpeg' || file.file.mime_type == 'video/mp4') {
                    return true;
                }
                return false;
            }
        },
        mounted: function () {

            axios.get(route('ajax.categories.index')+'?scope=slides').then((response) => {
                this.categories = response.data.data;
            });
            axios.get(route('ajax.slides.index')+'?sortable_field=created_at&sortable_direction=DESC').then((response) => {
                this.files = response.data.data;
                this.pagination = response.data.meta.pagination;
            });
        }
    }
</script>
<style lang="scss">
</style>
