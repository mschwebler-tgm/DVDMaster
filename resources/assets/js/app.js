require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import router from './router';

import theMovieDb from './themoviedb';

Vue.component('movie-cards', require('./components/MovieCards.vue'));

Vue.use(VueRouter);

const app = new Vue({
    el: '#app',
    router,
    data() {
        return {
            theMovieDb,
            tmdbImagePath: 'https://image.tmdb.org/t/p/'
        }
    },
    mounted() {
        M.AutoInit();
    }
});
