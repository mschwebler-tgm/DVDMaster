<template>
    <div style="margin-top: 20px;">
        <div class="container flex-box flex-justify-end" style="padding-bottom: 15px">
            <span style="margin-right: 20px;">Autocomplete</span>
            <div class="switch">
                <label>
                    Off
                    <input type="checkbox" v-model="autocomplete">
                    <span class="lever"></span>
                    On
                </label>
            </div>
        </div>
        <div v-show="!autocomplete">
            <div class="container z-depth-3">
                <div class="row">
                    <div class="input-field col s6">
                        <input id="custom_title" type="text" class="validate" v-model="customMovie.title">
                        <label for="custom_title">Title</label>
                    </div>
                    <div class="input-field col s6">
                        <star-rating :increment="0.5" style="z-index: 1000;" inactive-color="#e6e6e6" class="right"
                                     active-color="#FFD700" v-model="customMovie.custom_rating" :show-rating="false"
                                     :border-width="0" :star-size="40"></star-rating>
                    </div>
                    <div class="input-field col s12">
                        <textarea id="custom_overview" class="validate materialize-textarea" v-model="customMovie.overview"></textarea>
                        <label for="custom_overview">Overview</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <genres-input @change="genres => customMovie.genres = genres"></genres-input>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <actors-input @change="actors => customMovie.actors = actors"></actors-input>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s3">
                        <input type="text" class="datepicker">
                    </div>
                </div>
                <div class="row">
                    <div class="col s6">
                        <a class="waves-effect waves-light btn" @click="openCustomBackdropDialog()"><i class="material-icons left">collections</i>Background</a>
                        <a class="waves-effect waves-light btn" @click="clearBackdrop()" v-if="custom_backdrop_preview"><i class="material-icons left">clear</i>Clear</a>
                        <input type="file" id="custom_backdrop" style="display: none" accept="image/*" @change="previewBackdrop">
                        <img :src="custom_backdrop_preview" id="custom_backdrop_preview" />
                    </div>
                </div>
                <div class="row">
                    <div class="col s6">
                        <a class="waves-effect waves-light btn" @click="openCustomCoverDialog()"><i class="material-icons left">collections_bookmark</i>Cover</a>
                        <a class="waves-effect waves-light btn" @click="clearCover()" v-if="custom_poster_preview"><i class="material-icons left">clear</i>Clear</a>
                        <input type="file" id="custom_cover" style="display: none" accept="image/*" @change="previewCover">
                        <img :src="custom_poster_preview" id="custom_cover_preview" />
                    </div>
                </div>
            </div>
        </div>

        <div class="container hide-on-small-only z-depth-3" v-show="autocomplete">
            <div class="movie-backdrop" :style="{'background-image': movie.backdrop_path ? 'url(' + $root.getImagePath(movie.backdrop_path, 'original') + ')' : ''}">
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
                                <movie-rating :movie="movie"></movie-rating>
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
                        <img :src="movie.poster_path ? ($root.getImagePath(movie.poster_path, 'w185'))
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
                customMovie: {},
                suggestions: [],
                swiperOption: {
                    slidesPerView: window.innerWidth / 230,
                    freeMode: true,
                    spaceBetween: 0,
                },
                readyForSuggestions: true,
                collapsibleInstances: [],
                lastSearchTerm: '',
                searchTermChanged: false,
                autocomplete: true,
                custom_backdrop_preview: null,
                custom_poster_preview: null
            }
        },
        mounted() {
            this.swiperOption.slidesPerView = Math.floor($('#suggestions').width() / 135);
            this.readyForSuggestions = true;
            let elems = document.querySelectorAll('.collapsible');
            this.instances = M.Collapsible.init(elems);
            elems = document.querySelectorAll('.tooltipped');
            M.Tooltip.init(elems);
            elems = document.querySelectorAll('.datepicker');
            M.Datepicker.init(elems, {
                autoclose: true,
                format: 'dd. mm. yyyy',
                yearRange: [1950, (new Date()).getFullYear()],
                firstDay: 1,
                onSelect: dateEvent => {
                    let date = new Date(dateEvent);
                    let year = date.getFullYear();
                    let month = date.getMonth() < 10 ? '0' + date.getMonth() : date.getMonth();
                    let day = date.getDay() < 10 ? '0' + date.getDay() : date.getDay();
                    this.customMovie.release_date = year + '-' + month + '-' + day;
                },
            });
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
            openCustomCoverDialog() {
                $('#custom_cover').trigger('click');
            },
            openCustomBackdropDialog() {
                $('#custom_backdrop').trigger('click');
            },
            setPreviewImage(input, element) {
                if (input.files && input.files[0]) {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        this[element] = e.target.result;
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            },
            previewCover() {
                this.setPreviewImage($('#custom_cover')[0], 'custom_poster_preview');
            },
            previewBackdrop() {
                this.setPreviewImage($('#custom_backdrop')[0], 'custom_backdrop_preview');
            },
            clearCover() {
                $('#custom_cover').val(null);
                this.custom_poster_preview = null;
            },
            clearBackdrop() {
                $('#custom_backdrop').val(null);
                this.custom_backdrop_preview = null;
            },
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

    #custom_cover_preview, #custom_backdrop_preview {
        max-width: 100%;
    }

</style>