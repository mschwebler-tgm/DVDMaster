require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import router from './router';
import Transitions from 'vue2-transitions'
import theMovieDb from './themoviedb';
import store from './store';
import VueMaterial from 'vue-material';

import 'vue-material/dist/vue-material.min.css';

Vue.component('movie-cards', require('./components/MovieCards.vue'));
Vue.component('movie-card', require('./components/MovieCard.vue'));
Vue.component('user-modal', require('./components/UserModal.vue'));
Vue.component('retrieval-modal', require('./components/RetrievalModal.vue'));
Vue.component('movie-rating', require('./components/common/MovieRating.vue'));
Vue.component('actors-input', require('./components/common/ActorsInput.vue'));
Vue.component('genres-input', require('./components/common/GenresInput.vue'));
Vue.component('search-bar', require('./components/SearchBar.vue'));
// Vue.component('movie-filter', require('./components/MovieFilter.vue'));
Vue.component('paginator', require('./components/common/Paginator.vue'));
Vue.component('loader', require('./components/common/Loader.vue'));
Vue.component('dashboard', require('./components/Dashboard.vue'));

// Home page
Vue.component('movie-list', require('./components/HomePage/ListView.vue'));
Vue.component('movie-list-item', require('./components/HomePage/ListViewItem.vue'));
Vue.component('home-filter', require('./components/HomePage/Filter.vue'));

// static
Vue.component('root', require('./components/static/Root.vue'));

Vue.use(VueRouter);
Vue.use(Transitions);
Vue.use(VueMaterial);

const app = new Vue({
    el: '#app',
    router,
    store,
    data() {
        return {
            theMovieDb,
            tmdbImagePath: 'https://image.tmdb.org/t/p/',
            showLoading: false,
            showSidepanel: false,
            remember: false , // remember checbox on login page needs a model
            toastShow: false,
            toastDuration: 3000,
            toastText: ''
        }
    },
    created() {
        this.injectRootInstanceIntoStore();
    },
    methods: {
        getImagePath(path, resolution) {
            if (!path) {
                return 'https://x1.xingassets.com/assets/frontend_minified/img/users/nobody_m.original.jpg';
            }
            if (path.split('/')[1] === 'storage') {
                return path;
            }
            return this.tmdbImagePath + resolution + path;
        },
        toLogin() {
            window.location = '/login';
        },
        toRegistration() {
            window.location = '/register';
        },
        logout() {
            axios.post('/logout').then(() => {
                window.location = '/login';
            });
        },
        toast(str, duration) {
            this.toastText = str;
            let oldDuration = this.duration;
            if (duration) { this.duration = duration }

            this.toastShow = true;
            setTimeout(() => {
                this.duration = oldDuration;
                this.str = '';
            }, this.duration + 100);
        },
        injectRootInstanceIntoStore() {
            this.$store.commit('MOVIES_SET_ROOT', this);
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