<template>
    <div>
        <h6>Layer manager</h6>
        <ul v-if="this.simple" class="list-group">
            <li :class="{active: (activeElement && activeElement.name === element.name), hover: (hoverElement && hoverElement.name === element.name)}"
                class="list-group-item" v-for="(element, index) in elementOrder" :key="index">
                {{elements[element.name].properties.prettyname ?
                elements[element.name].properties.prettyname : element.name}}
            </li>
        </ul>
        <draggable v-if="!this.simple" :ghost-class="''" tag="ul" class="list-group" v-model="elementOrder" group="slidemeisterElements"
                   @start="drag=true" @end="dragEnd">
            <li :class="{active: (activeElement && activeElement.name === element.name), hover: (hoverElement && hoverElement.name === element.name)}"
                class="list-group-item" v-for="(element, index) in elementOrder" :key="index">
                {{elements[element.name].properties.prettyname ?
                elements[element.name].properties.prettyname : element.name}}
            </li>
        </draggable>
    </div>
</template>
<script>
    import draggable from 'vuedraggable';

    export default {
        name: 'partymeister-slides-layers',
        props: ['elements', 'activeElement', 'hoverElement', 'simple'],
        components: {
            draggable,
        },
        data: () => ({
            elementOrder: [],
        }),
        mounted() {
            this.$eventHub.$on('partymeister-slides:all-elements', (data) => {
                this.elementOrder = data.order;
            });
        },
        methods: {
            dragEnd($event) {
                this.$eventHub.$emit('partymeister-slides:new-element-order', this.elementOrder);
            }
        }
    }
</script>
<style scoped>
    .sortable-chosen {
        background-color: #cbe9f5;
        border-color: #cbe9f5;
    }
    .hover {
        background-color: #cae9f4;
        border-color: #cae9f4;
    }
</style>
