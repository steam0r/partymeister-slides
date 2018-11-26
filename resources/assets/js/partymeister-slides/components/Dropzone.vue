<template>
    <div id="vuedropzone" :style="{ zIndex: zIndex }">
        <draggable v-model="droppedFiles" :options="{group:'files'}" @add="onAdd" class="draggable-container">
        </draggable>
    </div>
</template>

<script>
    import draggable from 'vuedraggable';

    export default {
        name: 'partymeister-slides-dropzone',
        data: function () {
            return {
                droppedFiles: [],
                zIndex: -1
            };
        },
        components: {
            draggable,
        },
        methods: {
            onAdd: function (event) {
                this.$eventHub.$emit('partymeister-slides:image-dropped', this.droppedFiles[event.newIndex].file.file_original_relative);
            },
            isImage: function (file) {
                if (file.file.mime_type == 'image/png' || file.file.mime_type == 'image/jpg') {
                    return true;
                }
                return false;
            },
        },
        mounted: function () {
            this.$eventHub.$on('mediapool:drag:start', () => {
                console.log("drag start");
                this.zIndex = 10000;
            });
            this.$eventHub.$on('mediapool:drag:end', () => {
                console.log("drag end");
                this.zIndex = -1;
            });
        }
    }
</script>
<style lang="scss">
</style>
