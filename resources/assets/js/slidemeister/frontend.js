window.Vue = require('vue');


Vue.prototype.$eventHub = new Vue();

require('./main');

// Initialize base vue app
const app = new Vue({
    el: '#app'
});
