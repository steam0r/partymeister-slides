<template>
    <button @click="openFileDialogue"
            class="btn btn-block btn-primary btn-sm">Import
        <input class="file-upload" style="display: none;" type="file" @change="loadTextFromFile">
    </button>
</template>

<script>
    export default {
        methods: {
            openFileDialogue() {
                document.querySelector('.file-upload').click();
            },
            loadTextFromFile(ev) {
                const file = ev.target.files[0];
                const reader = new FileReader();

                reader.onload = (e) =>  {
                    this.$eventHub.$emit("partymeister-slides:file-loaded", e.target.result);
                };
                reader.readAsText(file);
            }
        }
    };
</script>
