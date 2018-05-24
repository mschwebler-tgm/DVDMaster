<template>
    <div style="margin-top: 20px;">
        <div class="hide-on-med-and-up">

        </div>
        <div class="container hide-on-small-only z-depth-3">
            <div class="movie-backdrop" :style="{'background-image': 'url(' + $root.tmdbImagePath + 'original' + movie.backdrop_path + ')'}">
                <div class="row">
                    <i class="material-icons clear-movie tooltipped" data-position="right" data-tooltip="Clear" @click="clearMovie">clear</i>
                    <div class="col m6">
                        <div class="input-field">
                            <input id="title" type="text" class="form-control white-text" name="name" @blur="searchMovie" v-model="movie.title">
                            <label for="title">Title</label>
                        </div>
                    </div>
                    <div class="col m6 center">
                        <img :src="movie.poster_path ? ($root.tmdbImagePath + 'w185' + movie.poster_path)
                                                   : ('https://dentallabor-gruettner.de/wp-content/uploads/2017/05/placeholder.gif')"
                             class="movie-poster background-center">
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

            <!--<div class="row">-->
                <!--<div class="col s12 z-depth-5 stage">-->
                    <!--<div style="position: relative; max-height: 500px; overflow: hidden;">-->
                        <!--<img :src="movie.backdrop_path ? ($root.tmdbImagePath + 'original' + movie.backdrop_path) :-->
                                                     <!--('https://dentallabor-gruettner.de/wp-content/uploads/2017/05/placeholder.gif')"-->
                             <!--class="featured-cover background-center">-->
                        <!--<div style="position: absolute; top: 0; left: 0; display: flex; align-items: flex-end; width: 100%; height: 100%">-->
                            <!--<div style="background-color: rgba(0,0,0,0.33); padding: 35px; width: 100%">-->
                                <!--<div class="input-field">-->
                                    <!--<input id="title" type="text"-->
                                           <!--class="form-control white-text"-->
                                           <!--name="name" @blur="searchMovie" v-model="movie.title">-->
                                    <!--<label for="title" class="white-text">Title</label>-->
                                <!--</div>-->
                                <!--&lt;!&ndash;<h1 class="white-text" style="margin-top: 0;">{{ movie.title }}</h1>&ndash;&gt;-->
                                <!--<p class="tagline">{{ movie.tagline }}</p>-->
                            <!--</div>-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</div>-->

                <!--<div class="col s12" style="background-color: white;">-->
                <!--</div>-->
            <!--</div>-->
        </div>
    </div>
</template>

<script>
    import '../../../../node_modules/swiper/dist/css/swiper.css';
    import {swiper, swiperSlide} from 'vue-awesome-swiper';

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
                collapsibleInstances: []
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
                this.$root.theMovieDb.search.getMovie({query: title}, res => {
                    res = JSON.parse(res);
                    if (res.results && res.results.length > 0) {
                        this.suggestions = res.results;
                        this.movie = res.results.shift();

                        $('#suggestions li:not(.active) #suggestionsTrigger').trigger('click');
                    }
                }, err => {
                    console.log(err);
                });
            },
            selectMovie(movie) {
                this.movie = movie;
            },
            clearMovie() {
                this.movie = {};
                this.suggestions = [];
            }
        },
        components: {
            swiper,
            swiperSlide
        }
    }
</script>

<style scoped>
    .movie-poster {
        width: 185px;
        height: 278px;
        border: 3px groove #adbfbf;
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

</style>