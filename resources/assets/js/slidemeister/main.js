import VueForceNextTick from 'vue-force-next-tick';
Vue.use(VueForceNextTick);

Vue.component(
    'partymeister-slides-dropzone',
    require('./components/Dropzone.vue').default
);
Vue.component(
    'partymeister-slides-elements',
    require('./components/Elements.vue').default
);

Vue.component(
    'partymeister-slides-controls',
    require('./components/Controls.vue').default
);

Vue.component(
    'partymeister-slides-actions',
    require('./components/Actions.vue').default
);

Vue.component(
    'partymeister-slides-layers',
    require('./components/Layers.vue').default
);

Vue.component(
    'partymeister-slides-properties',
    require('./components/Properties.vue').default
);

Vue.component(
    'color-picker',
    require('./components/ColorPicker.vue').default
);
