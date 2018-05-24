<template>
    <div style="margin-top: 20px;">
        <div class="hide-on-med-and-up">

        </div>
        <div class="container hide-on-small-only z-depth-3">
            <div class="movie-backdrop" :style="{'background-image': movie.backdrop_path ? 'url(' + $root.tmdbImagePath + 'original' + movie.backdrop_path + ')' : ''}">
                <div class="row" style="height: 420px;">
                    <i class="material-icons clear-movie tooltipped" data-position="right" data-tooltip="Clear" @click="clearMovie">clear</i>
                    <div class="col m6">
                        <div class="row" style="padding: 15px;">
                            <div class="col s8">
                                <div class="input-field">
                                    <input id="title" type="text" class="form-control white-text" name="name"
                                           v-model="movie.title" @blur="searchMovie" @keydown.enter="searchMovie" @keydown.tab="searchMovie" @keydown="registerChange">
                                    <label for="title">Title</label>
                                </div>
                            </div>
                            <div class="col s12 ratings">
                                <div class="star-ratings">
                                <star-rating :increment="0.5" style="z-index: 1000;" class="custom-rating" inactive-color="white"
                                             active-color="#FFD700" v-model="movie.custom_rating" :show-rating="false"
                                             :border-width="0" :star-size="40"></star-rating>
                                <star-rating :increment="0.01" :rating="movie.vote_average/2" :show-rating="false" class="tmdb-rating"
                                             active-color="#aaaaaa" inactive-color="white" :border-width="0"
                                             v-if="!movie.custom_rating" :star-size="40"></star-rating>
                                </div>
                                <fade-transition :duration="200">
                                    <i class="material-icons white-text pointer tooltipped" data-position="right"
                                       :data-tooltip="movie.vote_count + ' users rated on themoviedb.org'"
                                       v-show="movie.vote_count && !movie.custom_rating">info</i>
                                </fade-transition>
                            </div>
                        </div>
                        <div class="input-field col m6 movie-overview">
                            <textarea id="overview" class="materialize-textarea grey-text" v-model="movie.overview"></textarea>
                            <label for="overview">Overview</label>
                        </div>
                    </div>
                    <div class="col m6 poster-container">
                        <img :src="movie.poster_path ? ($root.tmdbImagePath + 'w185' + movie.poster_path)
                                                   : ('https://dentallabor-gruettner.de/wp-content/uploads/2017/05/placeholder.gif')"
                             class="movie-poster background-center">
                        <p v-if="movie.release_date" class="grey-text" style="font-size: 14px;">
                            {{ movie.release_date.substring(0, 4) }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <ul class="collapsible" id="suggestions">
                    <li>
                        <div class="collapsible-header" id="suggestionsTrigger"></div>
                        <div class="collapsible-body">
                            <div class="row">
                                <div class="col s12 suggestions" style="background-color: black;">
                                    <!-- swiper -->
                                    <swiper :options="swiperOption" v-if="readyForSuggestions">
                                        <swiper-slide v-for="suggestion in suggestions" :key="suggestion.id"
                                        v-if="suggestion.poster_path">
                                            <div class="movie-sugg">
                                            <div class="movie-sugg-cover" :class="{'movie-sugg-active': movie.id === suggestion.id}">
                                                <img :src="$root.tmdbImagePath + 'w185' + suggestion.poster_path"
                                                     class="background-center" @click="selectMovie(suggestion)"/>
                                            </div>
                                        </div>
                                        </swiper-slide>
                                    </swiper>
                                <div class="hide-indicator center" @click="readyForSuggestions = false">
                                    <i class="material-icons">keyboard_arrow_up</i>
                                </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col s12">
                    <a class="waves-effect waves-light btn right" @click="save">Save</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import '../../../../node_modules/swiper/dist/css/swiper.css';
    import {swiper, swiperSlide} from 'vue-awesome-swiper';
    import StarRating from 'vue-star-rating';

    export default {
        name: "AddMovie",
        data() {
            return {
                movie: {},
                suggestions: [],
                swiperOption: {
                    slidesPerView: window.innerWidth / 230,
                    freeMode: true,
                    spaceBetween: 0,
                },
                readyForSuggestions: true,
                collapsibleInstances: [],
                lastSearchTerm: '',
                searchTermChanged: false
            }
        },
        mounted() {
            this.swiperOption.slidesPerView = Math.floor($('#suggestions').width() / 135);
            this.readyForSuggestions = true;
            let elems = document.querySelectorAll('.collapsible');
            this.instances = M.Collapsible.init(elems);
            elems = document.querySelectorAll('.tooltipped');
            M.Tooltip.init(elems);
        },
        methods: {
            searchMovie() {
                let title = $('#title').val();
                if (title === this.lastSearchTerm || !this.searchTermChanged) { return }
                this.$root.theMovieDb.search.getMovie({query: title}, res => {
                    res = JSON.parse(res);
                    if (res.results && res.results.length > 0) {
                        this.suggestions = res.results;
                        this.movie = res.results.shift();
                        this.lastSearchTerm = title;
                        this.searchTermChanged = false;

                        $('#suggestions li:not(.active) #suggestionsTrigger').trigger('click');
                        $('.movie-overview label').addClass('active');

                        setTimeout(() => {
                            M.textareaAutoResize($('#overview'));
                        }, 100);
                    }
                }, err => {
                    console.log(err);
                });
            },
            selectMovie(movie) {
                this.movie = movie;
                setTimeout(() => {
                    M.textareaAutoResize($('#overview'));
                }, 100);
            },
            clearMovie() {
                this.movie = {};
                this.suggestions = [];
            },
            registerChange(event) {
                console.log(event);
                if (!this.searchTermChanged) {
                    this.searchTermChanged = ['Enter', 'Tab', 'Control', 'Alt', 'Shift', 'Capslock', 'Escape',
                        'F1', 'F2', 'F3', 'F4', 'F5', 'F6', 'F7', 'F8', 'F9', 'F10', 'F11', 'F12',
                        'AudioVolumeMute', 'AudioVolumeDown', 'AudioVolumeUp', 'End', 'PageDown', 'PageUp',
                        'Home', 'Insert', 'NumLock'].indexOf(event.key) === -1;
                }
            },
            save() {
                this.$root.showLoading = true;
                axios.post('/api/movie', { movie: this.movie }).then(res => {
                    console.log(res);
                    this.$root.showLoading = false;
                }).catch(err => {
                    console.log(err);
                    this.$root.showLoading = false;
                });
            }
        },
        components: {
            swiper,
            swiperSlide,
            StarRating
        }
    }
</script>

<style scoped>
    .movie-poster {
        width: 185px;
        height: 278px;
        border: 3px groove #adbfbf;
        margin-top: 15px;
    }

    #title {
        font-size: 26px;
    }

    label[for='title'] {
        font-size: 20px;
    }

    .stage {
        padding: 0;
    }

    .suggestions {
        -webkit-transition: height .35s ease-in-out;
        -moz-transition: height .35s ease-in-out;
        -ms-transition: height .35s ease-in-out;
        -o-transition: height .35s ease-in-out;
        transition: height .35s ease-in-out;
        overflow: hidden;
        position: relative;
    }

    .swiper-container {
        height: 100%;
    }

    .swiper-slide {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .movie-sugg {
        width: 100%;
        height: 100%;
        position: relative;
    }

    .movie-sugg-cover {
        margin: 15px;
    }

    .movie-sugg-cover:hover {
        outline: 2px solid white;
        border: 2px solid white;
        transition: all .15s ease-in-out;
        cursor: pointer;
    }

    .movie-sugg-active {
        outline: 2px solid white;
        border: 2px solid white;
    }

    .hide-indicator {
        position: absolute;
        bottom: 0;
        left: calc(50% - 20px);
        width: 40px;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        background-color: rgba(0, 0, 0, 0.67);
        z-index: 100;
        opacity: 0;
        cursor: pointer;
        -webkit-transition: opacity .5s ease-in-out;
        -moz-transition: opacity .5s ease-in-out;
        -ms-transition: opacity .5s ease-in-out;
        -o-transition: opacity .5s ease-in-out;
        transition: opacity .5s ease-in-out;
    }

    #suggestions:hover .hide-indicator {
        opacity: 1;
    }

    #suggestionsTrigger {
        height: 0;
        padding: 0;
        border: none;
    }

    #suggestions {
        border: none;
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        box-shadow: none;
        margin: 0;
    }

    #suggestions .collapsible-body {
        border: none;
        padding: 0;
    }

    #suggestions .collapsible-body > .row {
        margin-bottom: 0;
    }

    .movie-backdrop {
        background-repeat: no-repeat;
        background-size: cover;
        position: relative;
    }

    .movie-backdrop > .row {
        padding: 15px;
        margin-bottom: 0;
        background: linear-gradient(
            rgba(0, 0, 0, 0.65),
            rgba(0, 0, 0, 0.65)
        );
    }

    .clear-movie {
        cursor: pointer;
        position: absolute;
        top: 0;
        right: 0;
        padding: 15px;
        color: white;
        font-size: 34px;
    }

    .tmdb-rating {
        position: absolute;
        top: 0;
    }

    .star-ratings:hover .custom-rating {
        display: flex;
    }

    .star-ratings:hover .tmdb-rating {
        display: none;
    }

    .custom-rating {
        display: none;
    }

    .ratings {
        position: relative;
        display: flex;
        justify-content: flex-start;
        align-items: center;
    }

    .ratings i {
        padding-left: 10px;
    }

    .movie-overview {
        position: absolute;
        bottom: 15px;
        left: 15px;
        max-height: 170px;
        overflow: scroll;
    }

    .movie-overview::-webkit-scrollbar {
        background-color: rgba(0,0,0,0);
        width: 8px;
        height: 8px;
    }
    .movie-overview::-webkit-scrollbar-button {
        display: none;
    }
    .movie-overview::-webkit-scrollbar-track {
        display: none;
    }
    .movie-overview::-webkit-scrollbar-track-piece {
    }
    .movie-overview::-webkit-scrollbar-thumb {
        background-color: rgba(125,125,125,0.5);
        border-radius: 10px;
    }
    .movie-overview::-webkit-scrollbar-corner {
        display: none;
    }
    .movie-overview::-webkit-resizer {
        display: none;
    }

    .poster-container {
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

</style>