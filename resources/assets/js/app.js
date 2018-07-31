require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import router from './router';
import Transitions from 'vue2-transitions'
import theMovieDb from './themoviedb';

Vue.component('movie-cards', require('./components/MovieCards.vue'));
Vue.component('movie-card', require('./components/MovieCard.vue'));
Vue.component('user-modal', require('./components/UserModal.vue'));
Vue.component('movie-rating', require('./components/common/MovieRating.vue'));
Vue.component('movie-list', require('./components/MovieList.vue'));
Vue.component('actors-input', require('./components/common/ActorsInput.vue'));
Vue.component('genres-input', require('./components/common/GenresInput.vue'));

Vue.use(VueRouter);
Vue.use(Transitions);

const app = new Vue({
    el: '#app',
    router,
    data() {
        return {
            theMovieDb,
            tmdbImagePath: 'https://image.tmdb.org/t/p/',
            showLoading: false
        }
    },
    mounted() {
        M.AutoInit();
    },
    methods: {
        getImagePath(path, resolution) {
            if (!path) {
                return 'https://x1.xingassets.com/assets/frontend_minified/img/users/nobody_m.original.jpg';
            }
            if (path.split('/')[0] === 'storage') {
                return path;
            }
            return this.tmdbImagePath + resolution + path;
        }
    }
});

/**
 backdrop_sizes: [
     "w300",
     "w780",
     "w1280",
     "original"
 ],
 logo_sizes: [
     "w45",
     "w92",
     "w154",
     "w185",
     "w300",
     "w500",
     "original"
 ],
 poster_sizes: [
     "w92",
     "w154",
     "w185",
     "w342",
     "w500",
     "w780",
     "original"
 ],
 profile_sizes: [
     "w45",
     "w185",
     "h632",
     "original"
 ],
 still_sizes: [
     "w92",
     "w185",
     "w300",
     "original"
 ]
 */