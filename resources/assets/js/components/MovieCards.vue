<template>
    <div>
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
                <div class="card">
                    <div class="card-image">
                        <img :src="'https://image.tmdb.org/t/p/w500' + movie.backdrop_path">
                        <span class="card-title">{{ movie.title }}</span>
                    </div>
                    <div class="card-content movie-overview">
                        <p>{{ movie.overview }}</p>
                    </div>
                    <div class="card-action">
                        <a href="#"><i class="material-icons">trending_up</i>&nbsp;&nbsp;{{ movie.vote_average }}<span
                                class="text-muted">&nbsp;({{ movie.vote_count}})</span></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col s12 center" v-if="movies.length === 0 && !featuredMovie">
            No movies in Database
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                movies: [],
                featuredMovie: null
            }
        },
        created() {
            axios.get('/api/movies').then((res) => {
                let movies = res.data;
                this.featuredMovie = movies.shift();
                this.movies = movies;
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

    .movie-overview {
        width: 100%;
        max-height: 120px;
        overflow: hidden;
        -ms-text-overflow: ellipsis;
        text-overflow: ellipsis;
    }

    .stage {
        padding: 0;
    }

    .movie-cards {
        margin-top: 25px;
    }
</style>