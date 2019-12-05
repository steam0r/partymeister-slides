import Vue from 'vue';

import VueForceNextTick from 'vue-force-next-tick';
Vue.use(VueForceNextTick);

Vue.component(
    'slidemeister-viewer',
    require('./components/SlidemeisterViewer.vue').default
);

Vue.component(
    'slidemeister-connector',
    require('./components/SlidemeisterConnector.vue').default
);

// Initialize global event hub
Vue.prototype.$eventHub = new Vue();

// Initialize base vue app
window.VueApp = new Vue({
    el: '#app',
});

window.Vue = Vue;
