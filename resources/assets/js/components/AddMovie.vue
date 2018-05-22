<template>
    <div class="container">
        <div class="row">
            <div class="col s12 z-depth-5 stage">
                <div style="position: relative; max-height: 500px; overflow: hidden;">
                    <img :src="movie.backdrop_path ? ($root.tmdbImagePath + 'original' + movie.backdrop_path) :
                                                     ('https://dentallabor-gruettner.de/wp-content/uploads/2017/05/placeholder.gif')"
                         class="featured-cover background-center">
                    <div style="position: absolute; top: 0; left: 0; display: flex; align-items: flex-end; width: 100%; height: 100%">
                        <div style="background-color: rgba(0,0,0,0.33); padding: 35px; width: 100%">
                            <div class="input-field">
                                <input id="title" type="text"
                                       class="form-control white-text"
                                       name="name" @blur="searchMovie" v-model="movie.title">
                                <label for="title" class="white-text">Title</label>
                            </div>
                            <!--<h1 class="white-text" style="margin-top: 0;">{{ movie.title }}</h1>-->
                            <p class="tagline">{{ movie.tagline }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12 suggestions" id="suggestions" style="background-color: black;">
                <!-- swiper -->
                <swiper :options="swiperOption" v-if="readyForSuggestions">
                    <swiper-slide v-for="suggestion in suggestions" :key="suggestion.id" v-if="suggestion.poster_path">
                        <div class="movie-sugg">
                            <div class="movie-sugg-cover">
                                <img :src="$root.tmdbImagePath + 'w185' + suggestion.poster_path" class="background-center" @click="selectMovie(suggestion)"/>
                            </div>
                        </div>
                    </swiper-slide>
                </swiper>
                <div class="hide-indicator center" @click="readyForSuggestions = false">
                    <i class="material-icons">keyboard_arrow_up</i>
                </div>
            </div>
            <div class="col s12" style="background-color: white;">
            </div>
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
                readyForSuggestions: true
            }
        },
        mounted() {
            this.swiperOption.slidesPerView = Math.floor($('#suggestions').width() / 135);
            this.readyForSuggestions = true;
        },
        methods: {
            searchMovie() {
                let title = $('#title').val();
                this.$root.theMovieDb.search.getMovie({query: title}, res => {
                    res = JSON.parse(res);
                    if (res.results && res.results.length > 0) {
                        this.suggestions = res.results;
                        this.movie = res.results.shift();
                        $('#suggestions').addClass('open');
                    }
                }, err => {
                    console.log(err);
                });
            },
            selectMovie(movie) {
                this.movie = movie;
            }
        },
        components: {
            swiper,
            swiperSlide
        }
    }
</script>

<style scoped>
    .stage {
        padding: 0;
    }

    .suggestions {
        height: 0;
        -webkit-transition: height .35s ease-in-out;
        -moz-transition: height .35s ease-in-out;
        -ms-transition: height .35s ease-in-out;
        -o-transition: height .35s ease-in-out;
        transition: height .35s ease-in-out;
        overflow: hidden;
        position: relative;
    }

    .open {
        height: auto !important;
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

</style>