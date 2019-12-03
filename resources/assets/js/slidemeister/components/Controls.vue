<template>
    <div>
        <partymeister-slides-actions :standalone="this.standalone" :simple="this.simple"
                                     :active-element="activeElement"></partymeister-slides-actions>
        <hr>
        <partymeister-slides-layers :simple="this.simple" :elements="elements" :element-order="elementOrder"
                                    :active-element="activeElement"
                                    :hover-element="hoverElement"></partymeister-slides-layers>
        <hr>
        <partymeister-slides-properties :simple="this.simple"></partymeister-slides-properties>
    </div>
</template>
<script>

    import PartymeisterSlidesActions from "../components/Actions";
    import PartymeisterSlidesLayers from "../components/Layers";
    import PartymeisterSlidesProperties from "../components/Properties";

    export default {
        name: 'partymeister-slides-controls',
        components: {PartymeisterSlidesProperties, PartymeisterSlidesLayers, PartymeisterSlidesActions},
        props: [
            'simple',
            'standalone'
        ],
        data: () => ({
            elements: {},
            elementOrder: [],
            activeElement: undefined,
            hoverElement: undefined,
        }),
        mounted() {
            this.$eventHub.$on('partymeister-slides:all-elements', (data) => {
                this.elements = data.elements;
                this.elementOrder = data.order;
            });
            this.$eventHub.$on('partymeister-slides:active-element', (data) => {
                this.elements = data.elements;
                this.activeElement = data.activeElement;
            });
            this.$eventHub.$on('partymeister-slides:mouseover', (index) => {
                this.hoverElement = index;
            });
            this.$eventHub.$on('partymeister-slides:mouseout', () => {
                this.hoverElement = undefined;
            });
        },
        methods: {}
    }
</script>
<style scoped>
</style>
