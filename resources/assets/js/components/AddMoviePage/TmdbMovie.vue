<template>
    <div style="overflow: hidden">
        <div class="movie-backdrop" :style="{'background-image': movie.backdrop_path ? 'url(' + $root.getImagePath(movie.backdrop_path, 'original') + ')' : ''}">
            <div>
                <md-icon class="pointer clear-icon" @click="clearMovie">clear</md-icon>
                <div class="pad input-fields">
                    <div class="flex">
                        <div style="width: 300px">
                            <md-field>
                                <label class="white-text">Title</label>
                                <md-input v-model="movie.title" placeholder="Title" id="title" class="white-text"
                                          @blur="searchMovie" @keydown.enter="searchMovie" @keydown.tab="searchMovie" @keydown="registerChange"></md-input>
                            </md-field>
                            <div class="flex flex-align-center">
                                <movie-rating :movie="movie" style="width: 200px; height: 46px"></movie-rating>
                                <fade-transition :duration="200">
                                    <div v-show="movie.vote_count && !movie.custom_rating">
                                        <md-icon style="color: darkgrey; margin-left: 10px">info</md-icon>
                                        <md-tooltip md-direction="right">{{ movie.vote_count }} users rated on themoviedb.org</md-tooltip>
                                    </div>
                                    <!--<i class="material-icons white-text pointer tooltipped" data-position="right"-->
                                    <!--:data-tooltip="movie.vote_count + ' users rated on themoviedb.org'"-->
                                    <!--v-show="movie.vote_count && !movie.custom_rating">info</i>-->
                                </fade-transition>
                            </div>
                        </div>
                        <div style="position: relative; flex: 1">
                            <div class="poster-container" :class="{'hide': hideDetails}">
                                <img :src="movie.poster_path ? ($root.getImagePath(movie.poster_path, 'w185'))
                                                       : ('https://dentallabor-gruettner.de/wp-content/uploads/2017/05/placeholder.gif')"
                                class="movie-poster background-center">
                                <p v-if="movie.release_date" class="grey-text" style="font-size: 14px;">
                                    {{ movie.release_date.substring(0, 4) }}
                                </p>
                                <div class="flex flex-justify-center" style="position: absolute; left: 0; bottom: -20px; width: 100%; height: 20px;">
                                    <md-icon style="color: white; background-color: rgba(0, 0, 0, 0.67);" class="pointer" @click="toggleDetails">keyboard_arrow_up</md-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                    <md-field v-if="showTextarea" style="width: calc(100% - 15px);">
                        <label class="white-text">Overview</label>
                        <md-textarea v-model="movie.overview" class="white-text" md-autogrow ref="overview"></md-textarea>
                    </md-field>
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
                                                <img :src="$root.getImagePath(suggestion.poster_path, 'w185')"
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
</template>

<script>
    import '../../../../../node_modules/swiper/dist/css/swiper.css';
    import {swiper, swiperSlide} from 'vue-awesome-swiper';
    import StarRating from 'vue-star-rating';

    export default {
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
                lastSearchTerm: '',
                searchTermChanged: false,
                showTextarea: true,
                hideDetails: true
            }
        },
        mounted() {
            this.swiperOption.slidesPerView = Math.floor($('#suggestions').width() / 135);
            this.readyForSuggestions = true;
        },
        methods: {
            searchMovie() {
                let title = $('#title').val();
                if (title === this.lastSearchTerm || !this.searchTermChanged) { return }
                this.$root.theMovieDb.search.getMovie({query: title}, res => {
                    res = JSON.parse(res);
                    if (res.results && res.results.length > 0) {
                        this.suggestions = res.results;
                        this.showTextarea = false;
                        this.movie = res.results.shift();
                        this.$nextTick(() => {
                            this.showTextarea = true;
                        });
                        this.lastSearchTerm = title;
                        this.searchTermChanged = false;
                        this.hideDetails = false;

                        $('#suggestions li:not(.active) #suggestionsTrigger').trigger('click');
                        $('.movie-overview label').addClass('active');
                    }
                }, err => {
                    console.log(err);
                });
            },
            selectMovie(movie) {
                this.showTextarea = false;
                this.movie = movie;
                this.$nextTick(() => {
                    this.showTextarea = true;
                });
            },
            clearMovie() {
                this.movie = {};
                this.suggestions = [];
            },
            registerChange(event) {
                if (!this.searchTermChanged) {
                    this.searchTermChanged = ['Enter', 'Tab', 'Control', 'Alt', 'Shift', 'Capslock', 'Escape',
                        'F1', 'F2', 'F3', 'F4', 'F5', 'F6', 'F7', 'F8', 'F9', 'F10', 'F11', 'F12',
                        'AudioVolumeMute', 'AudioVolumeDown', 'AudioVolumeUp', 'End', 'PageDown', 'PageUp',
                        'Home', 'Insert', 'NumLock'].indexOf(event.key) === -1;
                }
            },
            save() {
                let payload = {
                    movie: this.movie,
                    is_custom: false
                };
                if (!this.autocomplete) {
                    payload = new FormData();
                    payload.set('movie', JSON.stringify(this.customMovie));
                    payload.set('is_custom', 'true');
                    let coverInput = $('#custom_cover')[0];
                    if (coverInput.files && coverInput.files[0]) {
                        payload.append('custom_poster', coverInput.files[0], coverInput.files[0].name);
                    }
                    let backdropInput = $('#custom_backdrop')[0];
                    if (backdropInput.files && backdropInput.files[0]) {
                        payload.append('custom_backdrop', backdropInput.files[0], backdropInput.files[0].name);
                    }
                }
                this.$store.dispatch('MOVIES_ACTION_SAVE', payload);
            },
            toggleDetails() {
                this.hideDetails = !this.hideDetails;
                console.log('click');
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

    .clear-icon {
        position: absolute;
        top: 15px;
        right: 15px;
    }

    .white-text, .white-text::placeholder {
        color: white !important;
        -webkit-text-fill-color: white !important;;
    }

    .input-fields {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    .movie-poster {
        width: 185px;
        height: 278px;
        border: 3px groove #adbfbf;
    }

    #title {
        font-size: 26px;
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

    .movie-backdrop > div {
        height: 420px;
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
        height: calc(278px + 45px);
        position: absolute;
        top: 0;
        left: 15px;
        background-color: rgba(0, 0, 0, 0.67);
        padding: 30px 15px 15px;
        margin-right: 15px;
        margin-top: -30px;
        z-index: 10;
        width: calc(100% - 30px);
        transition: .3s ease-in-out;
    }

    .poster-container.hide {
        top: calc(-278px - 45px);
    }

    #custom_cover_preview, #custom_backdrop_preview {
        max-width: 100%;
    }
</style>