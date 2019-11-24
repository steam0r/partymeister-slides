import VueForceNextTick from 'vue-force-next-tick';
Vue.use(VueForceNextTick);

Vue.component(
    'partymeister-slides-mediapool',
    require('./components/Mediapool.vue').default
);

Vue.component(
    'partymeister-slides-playlist',
    require('./components/Playlist.vue').default
);

Vue.component(
    'partymeister-slides-dropzone',
    require('./components/Dropzone.vue').default
);


Vue.component(
    'partymeister-slides-elements',
    require('./components/partymeister-slides/Elements.vue').default
);

Vue.component(
    'partymeister-slides-controls',
    require('./components/partymeister-slides/Controls.vue').default
);

Vue.component(
    'partymeister-slides-actions',
    require('./components/partymeister-slides/Actions.vue').default
);

Vue.component(
    'partymeister-slides-layers',
    require('./components/partymeister-slides/Layers.vue').default
);

Vue.component(
    'partymeister-slides-properties',
    require('./components/partymeister-slides/Properties.vue').default
);

Vue.component(
    'color-picker',
    require('./components/partymeister-slides/ColorPicker.vue').default
);
