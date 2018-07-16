<template>
    <div class="container">
        <template v-if="loaded">
            <div class="row">
                <div class="col s12 z-depth-5 stage" v-if="featuredMovie">
                    <div style="position: relative; max-height: 500px; overflow: hidden;">
                        <img :src="'https://image.tmdb.org/t/p/original' + featuredMovie.backdrop_path"
                             class="featured-cover background-center">
                        <div style="position: absolute; top: 0; left: 0; display: flex; align-items: flex-end; width: 100%; height: 100%">
                            <div style="background-color: rgba(0,0,0,0.33); padding: 35px; width: 100%">
                                <h1 class="white-text" style="margin-top: 0;">{{ featuredMovie.title }}</h1>
                                <p class="tagline">{{ featuredMovie.tagline }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row movie-cards">
                <div v-for="movie in movies" class="col l3 m4 s12">
                    <movie-card :movie="movie"></movie-card>
                </div>
            </div>
            <div class="col s12 center" v-if="movies.length === 0 && !featuredMovie">
                No movies in Database.
                <router-link to="/addMovie">Add a movie</router-link>
            </div>
        </template>
        <template v-else>
            <div class="container center">
                <div class="preloader-wrapper active pre-loader">
                    <div class="spinner-layer spinner-red-only">
                        <div class="circle-clipper left">
                            <div class="circle"></div>
                        </div><div class="gap-patch">
                        <div class="circle"></div>
                    </div><div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                    </div>
                </div>
            </div>
        </template>

        <router-link to="/addMovie" class="btn-floating btn-large waves-effect waves-light add-movie"><i
                class="material-icons">add</i></router-link>
    </div>
</template>

<script>
    import MovieCard from "./MovieCard";
    export default {
        components: {MovieCard},
        data() {
            return {
                movies: [],
                featuredMovie: null,
                loaded: false
            }
        },
        created() {
            axios.get('/api/movies').then((res) => {
                let movies = res.data;
                this.featuredMovie = movies.shift();
                this.movies = movies;
                this.loaded = true;
            });
        }
    }
</script>

<style scoped>
    .tagline {
        font-size: 22px;
        color: white;
        font-family: 'Nunito', sans-serif;
    }

    .stage {
        padding: 0;
    }

    .movie-cards {
        margin-top: 25px;
    }

    .pre-loader {
        margin-top: 150px;
    }

    .add-movie {
        position: fixed;
        bottom: 30px;
        right: 30px;
    }
</style>