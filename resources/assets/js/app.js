require('./bootstrap');

window.Vue = require('vue');

Vue.component('movie-cards', require('./components/MovieCards.vue'));

const app = new Vue({
    el: '#app',
    mounted() {
        M.AutoInit();
    }
});
