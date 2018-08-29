require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import router from './router';
import Transitions from 'vue2-transitions'
import theMovieDb from './themoviedb';
import store from './store';
import VueMaterial from 'vue-material';

import 'vue-material/dist/vue-material.min.css';

Vue.component('user-modal', require('./components/MoviePage/UserModal.vue'));
Vue.component('retrieval-modal', require('./components/MoviePage/RetrievalModal.vue'));
Vue.component('movie-rating', require('./components/common/MovieRating.vue'));
Vue.component('actors-input', require('./components/common/ActorsInput.vue'));
Vue.component('genres-input', require('./components/common/GenresInput.vue'));
Vue.component('search-bar', require('./components/SearchBar.vue'));
Vue.component('paginator', require('./components/common/Paginator.vue'));
Vue.component('dashboard', require('./components/Dashboard.vue'));
Vue.component('loader', require('./components/common/Loader.vue'));

// Home page
Vue.component('movie-list', require('./components/DVDPage/ListView.vue'));
Vue.component('movie-list-item', require('./components/DVDPage/ListViewItem.vue'));
Vue.component('home-filter', require('./components/DVDPage/Filter.vue'));
Vue.component('movie-cards', require('./components/DVDPage/MovieCards.vue'));
Vue.component('movie-card', require('./components/DVDPage/MovieCard.vue'));

// Add movie
Vue.component('add-movie-custom', require('./components/AddMoviePage/Custom.vue'));
Vue.component('add-move-tmdb', require('./components/AddMoviePage/TmdbMovie.vue'));

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
            toastText: '',
            isMobile: false
        }
    },
    created() {
        this.injectRootInstanceIntoStore();
        let check = false;
        (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
        this.isMobile = check;
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
    },
    computed: {
        isAdmin() {
            return window.isAdmin;
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