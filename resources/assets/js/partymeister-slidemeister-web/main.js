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

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '12345',
    wsHost: window.location.hostname,
    wsPort: 6001,
    wsPath: '/socket',
    disableStats: true,
});

// window.Echo = new Echo({
//     broadcaster: 'socket.io',
//     // host: 'https://pm.demoparty.be/socket.io'
//     host: window.location.hostname + '/socket.io'
// });

window.Vue = Vue;
