<template>
    <div>
        <div v-if="standalone">
            <div class="form-group"><label for="name" class="control-label required">Name</label>
                <input required="required"
                       @keyup="updateId"
                       type="text"
                       id="name"
                       v-model="templateId"
                       class="form-control">
            </div>
            <div class="form-group"><label for="template_for" class="control-label">Template for</label>
                <select v-model="templateType"
                        @change="updateType"
                        id="template_for" class="form-control">
                    <option value="basic">Basic template</option>
                    <option value="coming_up">Coming up</option>
                    <option value="now">Now</option>
                    <option value="end">End of compo</option>
                    <option value="competition_entry_1">Competition (Entry 1)</option>
                    <option value="competition">Competition</option>
                    <option value="timetable">Timetable</option>
                    <option value="participants">Participants</option>
                    <option value="prizegiving">Prizegiving</option>
                    <option value="comments">Competition comments</option>
                </select>
            </div>
            <div>
                <button @click="saveTemplate" class="btn btn-block btn-success btn-sm">Save</button>
            </div>
            <hr>
        </div>
        <div v-if="!simple">
            <h6>Layers</h6>
            <div class="btn-group btn-block" role="group" aria-label="Basic example">
                <button @click="addElement" class="btn btn-success btn-sm">Add</button>
                <button :disabled="activeElement === undefined" @click="cloneElement"
                        class="btn btn-warning btn-sm">Clone
                </button>
                <button :disabled="activeElement === undefined" @click="deleteElement"
                        class="btn btn-danger btn-sm">Delete
                </button>
            </div>
        </div>
    </div>
</template>
<script>

    import elementNameHelper from "../mixins/elementNameHelper";
    import toast from "../mixins/toast";

    export default {
        name: 'partymeister-slides-actions',
        props: ['activeElement', 'simple', 'standalone'],
        mixins: [elementNameHelper, toast],
        data: () => ({
            templateId: '',
            templateType: 'basic'
        }),
        beforeDestroy() {
            this.$eventHub.$off();
        },
        mounted() {
            if (this.standalone) {
                this.$eventHub.$on('partymeister-slides:all-elements', (data) => {
                    this.templateId = data.id;
                    this.templateType = data.type;
                });
                this.$eventHub.$on('partymeister-slides:receive-definitions', (data) => {
                    let templates = localStorage.getItem('templates');
                    templates = JSON.parse(templates);
                    let definitions = JSON.parse(data.definitions);

                    // Check if this template already exists
                    let templateFound = false;
                    for (const [index, template] of templates.entries()) {
                        if (template.id === definitions.id) {
                            templateFound = index;
                        }
                    }

                    if (this.$route !== undefined && (this.$route.params.template !== undefined && this.$route.params.template !== null)) {
                        templates[this.$route.params.template] = JSON.parse(data.definitions);
                        localStorage.setItem('templates', JSON.stringify(templates));
                        this.toast('Template updated');
                    } else if (templateFound !== false) {
                        templates[templateFound] = JSON.parse(data.definitions);
                        localStorage.setItem('templates', JSON.stringify(templates));
                        this.toast('Template updated');
                        // Redirect to new template
                        this.$router.push({name: 'edit', params: {template: templateFound}})
                    } else {
                        templates.push(JSON.parse(data.definitions));
                        localStorage.setItem('templates', JSON.stringify(templates));
                        this.toast('Template created');
                        // Redirect to new template
                        this.$router.push({name: 'edit', params: {template: templates.length - 1}})
                    }
                });
            }
        },
        methods: {
            updateId() {
                this.$eventHub.$emit('partymeister-slides:update-id', this.templateId);
            },
            updateType() {
                this.$eventHub.$emit('partymeister-slides:update-type', this.templateType);
            },
            saveTemplate() {
                this.$eventHub.$emit('partymeister-slides:request-definitions', 'template-editor');
            },
            addElement() {
                const uniqid = 'element_' + this.createElementName();
                this.$eventHub.$emit('partymeister-slides:add-element', uniqid);
            },
            cloneElement() {
                const uniqid = 'element_' + this.createElementName();
                this.$eventHub.$emit('partymeister-slides:clone-element', {
                    uniqid: uniqid,
                    name: this.activeElement.name
                });
            },
            deleteElement() {
                this.$eventHub.$emit('partymeister-slides:delete-element', this.activeElement.name);
            }
        }
    }
</script>
<style>
</style>
