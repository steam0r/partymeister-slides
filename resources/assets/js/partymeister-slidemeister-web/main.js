import Vue from 'vue';

import VueAudio from 'vue-audio';
Vue.component('vue-audio', VueAudio);

window.axios = require('axios');

import VueForceNextTick from 'vue-force-next-tick';
Vue.use(VueForceNextTick);

Vue.component(
    'partymeister-slidemeister-web',
    require('./components/SlidemeisterWeb.vue').default
);

// Initialize global event hub
Vue.prototype.$eventHub = new Vue();

// Initialize base vue app
window.VueApp = new Vue({
    el: '#app',
});

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from "laravel-echo"

window.io = require('socket.io-client');

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001'
});

window.Vue = Vue;
