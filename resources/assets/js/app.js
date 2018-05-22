require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import router from './router';

Vue.component('movie-cards', require('./components/MovieCards.vue'));

Vue.use(VueRouter);

const app = new Vue({
    el: '#app',
    router,
    mounted() {
        M.AutoInit();
    }
});
