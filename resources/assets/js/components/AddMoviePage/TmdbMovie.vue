<template>
    <div style="overflow: hidden">
        <div class="md-elevation-3">
            <div class="movie-backdrop" :style="{'background-image': movie.backdrop_path ? 'url(' + $root.getImagePath(movie.backdrop_path, 'original') + ')' : ''}">
                <div>
                    <div class="clear-icon" @click="clearMovie">
                        <md-icon class="pointer white-text">clear</md-icon>
                        <md-tooltip md-direction="right">Clear</md-tooltip>
                    </div>
                    <div class="pad input-fields">
                        <div class="flex">
                            <div style="width: 270px">
                                <md-field class="md-custom-input">
                                    <label class="white-text">Title</label>
                                    <md-input v-model="movie.title" placeholder="Title" id="title" class="white-text"
                                              @blur="searchMovie" @keydown.enter="searchMovie" @keydown.tab="searchMovie" @keydown="registerChange"></md-input>
                                </md-field>
                                <div class="flex flex-align-center">
                                    <movie-rating :movie="movie" :showCustomRating="!!movie.custom_rating" style="width: 200px; height: 46px"></movie-rating>
                                    <fade-transition :duration="200">
                                        <div v-show="movie.vote_count && !movie.custom_rating">
                                            <md-icon style="color: darkgrey; margin-left: 10px">info</md-icon>
                                            <md-tooltip md-direction="right">{{ movie.vote_count }} users rated on themoviedb.org</md-tooltip>
                                        </div>
                                    </fade-transition>
                                </div>
                            </div>
                            <div style="position: relative; flex: 1">
                                <md-content class="poster-container md-xsmall-hide md-xlarge-hide flex md-accent md-elevation-3" :class="{'hide': hideDetails}">
                                    <div>
                                        <img :src="movie.poster_path ? ($root.getImagePath(movie.poster_path, 'w185'))
                                                           : ('https://dentallabor-gruettner.de/wp-content/uploads/2017/05/placeholder.gif')"
                                             class="movie-poster background-center">
                                    </div>
                                    <div style="flex: 1" class="pad">
                                        <template v-if="movie.release_date"><p><md-icon>calendar_today</md-icon>&nbsp;&nbsp;{{ movie.release_date }}</p></template>
                                        <template v-if="movie.runtime"><p><md-icon>timer</md-icon>&nbsp;&nbsp;{{ movie.runtime }} min</p></template>
                                        <template v-if="movie.homepage"><p><a :href="movie.homepage" target="_blank"><md-icon style="text-decoration: none">web</md-icon>&nbsp;&nbsp;<span style="color: var(--md-theme-default-text-primary-on-accent, #fff); text-decoration: underline;">Homepage</span></a></p></template>
                                        <md-chip v-for="genre in movie.genres" :key="genre.id" style="margin-bottom: 5px">{{ genre.name }}</md-chip>
                                    </div>
                                    <div class="flex flex-justify-center" style="position: absolute; left: 0; bottom: -24px; width: 100%; height: 24px; z-index: 100">
                                        <md-content class="md-accent">
                                            <md-icon class="pointer" id="detailsToggle">{{ hideDetails ? 'keyboard_arrow_down' : 'keyboard_arrow_up' }}</md-icon>
                                        </md-content>
                                    </div>
                                </md-content>
                            </div>
                        </div>
                        <md-field v-if="showTextarea" style="width: calc(100% - 15px);" class="overview md-custom-input">
                            <label class="white-text">Overview</label>
                            <md-textarea v-model="movie.overview" class="white-text" md-autogrow ref="overview"></md-textarea>
                        </md-field>
                    </div>
                    <div class="xlarge-poster-container pad flex flex-align-center">
                        <div class="flex">
                            <div>
                                <img :src="movie.poster_path ? ($root.getImagePath(movie.poster_path, 'w185'))
                                                           : ('https://dentallabor-gruettner.de/wp-content/uploads/2017/05/placeholder.gif')"
                                     class="movie-poster background-center">
                            </div>
                            <md-content class="md-accent pad white-text" style="background-color: transparent">
                                <template v-if="movie.release_date"><p><md-icon>calendar_today</md-icon>&nbsp;&nbsp;{{ movie.release_date }}</p></template>
                                <template v-if="movie.runtime"><p><md-icon>timer</md-icon>&nbsp;&nbsp;{{ movie.runtime }} min</p></template>
                                <template v-if="movie.homepage"><p><a :href="movie.homepage" target="_blank"><md-icon class="white-text" style="text-decoration: none">web</md-icon>&nbsp;&nbsp;<span style="color: var(--md-theme-default-text-primary-on-accent, #fff); text-decoration: underline;">Homepage</span></a></p></template>
                                <md-chip v-for="genre in movie.genres" :key="genre.id" class="md-accent" style="margin-bottom: 5px;">{{ genre.name }}</md-chip>
                            </md-content>
                        </div>
                    </div>
                </div>
            </div>
            <md-content class="pad md-accent">
                <span class="md-caption">Custom</span>
                <div class="pad">
                    <div class="white-text flex">
                        <md-checkbox v-model="movie.blue_ray"><md-icon>album</md-icon>&nbsp;&nbsp;Blue-Ray</md-checkbox><br>
                        <md-checkbox v-model="movie.based_on_book"><md-icon>event</md-icon>&nbsp;&nbsp;Based on book</md-checkbox><br>
                        <md-checkbox v-model="movie.true_story"><md-icon>book</md-icon>&nbsp;&nbsp;True Story</md-checkbox><br>
                    </div>
                    <md-field class="md-custom-input">
                        <label>Comment</label>
                        <md-input v-model="movie.comment" placeholder="Comment"></md-input>
                    </md-field>
                </div>
            </md-content>
            <div>
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
            </div>
        </div>
        <div class="flex flex-justify-end">
            <md-button class="md-raised md-accent" @click="save">Save</md-button>
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
            $('#detailsToggle').on('click', this.toggleDetails);
        },
        methods: {
            searchMovie() {
                let title = $('#title').val();
                if (title === this.lastSearchTerm || !this.searchTermChanged) { return }
                this.$root.theMovieDb.search.getMovie({query: title}, res => {
                    res = JSON.parse(res);
                    if (res.results && res.results.length > 0) {
                        this.suggestions = res.results;
                        this.getMovieDetails(res.results.shift().id);
                        $('#suggestions li:not(.active) #suggestionsTrigger').trigger('click');
                    } else {
                        this.$root.toast('No search results');
                    }
                }, err => {
                    this.$root.toast(err);
                });
            },
            getMovieDetails(id) {
                this.$root.theMovieDb.common.client({
                        url: "movie/" + id + this.$root.theMovieDb.common.generateQuery()
                    },
                    res => {
                        let movieRes = JSON.parse(res);
                        if (movieRes) {
                            this.showTextarea = false;
                            this.movie = movieRes;
                            this.$nextTick(() => {
                                this.showTextarea = true;
                            });
                            this.lastSearchTerm = title;
                            this.searchTermChanged = false;
                            this.hideDetails = false;
                        }
                    },
                    err => {
                        this.$root.toast(err);
                    }
                );
            },
            selectMovie(movie) {
                this.showTextarea = false;
                this.movie = movie;
                this.$nextTick(() => {
                    this.showTextarea = true;
                });
                this.getMovieDetails(movie.id);
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
                let payload = new FormData();
                payload.set('movie', JSON.stringify(this.movie));
                payload.set('is_custom', 'false');
                this.$store.dispatch('MOVIES_ACTION_SAVE', payload).then(() => {
                    this.movie.comment = null;
                    this.movie.blue_ray = null;
                    this.movie.true_story = null;
                    this.movie.based_on_book = null;
                });
            },
            toggleDetails() {
                this.hideDetails = !this.hideDetails;
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
        -webkit-text-fill-color: white !important;
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

    .poster-container {
        height: calc(278px + 45px);
        position: absolute;
        top: 0;
        left: 15px;
        background-color: rgba(128, 128, 128, 1);
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

    .overview {
        width: calc(100% - 15px);
    }

    .xlarge-poster-container {
        display: none;
    }

    @media only screen and (min-width: 1904px){
        .input-fields {
            float: left;
            width: 57%;
        }
        .xlarge-poster-container {
            display: block;
        }
    }

</style>