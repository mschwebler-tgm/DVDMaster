<template>
    <div class="container">
        <template v-show="loaded">
            <div class="row">
                <div class="col-s-12 z-depth-3"> <!-- toolbar -->
                    <div class="toolbar">
                        <div class="filters"> <!-- left -->
                            <i class="material-icons">filter_list</i>
                            <div class="genre-filter">
                                Genres&nbsp;&nbsp;
                                <input id="genre_filter" type="text" class="validate">
                            </div>
                            <div class="genre-filter">
                                Actors&nbsp;&nbsp;
                                <input id="actor_filter" type="text" class="validate">
                            </div>
                        </div> <!-- right -->
                        <div class="view-mode">
                            <i class="material-icons" @click="setViewMode('list')" :class="{ active: viewMode === 'list' }">view_list</i>
                            <i class="material-icons" @click="setViewMode('grid')" :class="{ active: viewMode === 'grid' }">view_module</i>
                        </div>
                    </div>
                </div>
                <div class="col s12 z-depth-5 stage pointer" v-if="featuredMovie && viewMode === 'grid'">
                    <div style="position: relative; max-height: 500px; overflow: hidden;"
                         @click="$root.$router.push('/movie/' + featuredMovie.id)">
                        <img :src="'https://image.tmdb.org/t/p/original' + featuredMovie.backdrop_path"
                             class="featured-cover background-center">
                        <div style="position: absolute; top: 0; left: 0; display: flex; align-items: flex-end; width: 100%; height: 100%">
                            <div style="background-color: rgba(0,0,0,0.33); padding: 35px; width: 100%">
                                <h1 class="white-text" style="margin-top: 0;">{{ featuredMovie.title }}</h1>
                                <p class="tagline">{{featuredMovie.tagline }}</p>
                            </div>
                        </div>
                    </div>
                </div> <!-- stage -->
            </div>
            <div class="row movie-cards" v-show="viewMode === 'grid'">
                <div v-for="(movie, index) in movies" class="col l3 m4 s12" v-if="index !== 0">
                    <movie-card :movie="movie"></movie-card>
                </div>
            </div>
            <div class="row movie-table" v-show="viewMode === 'list'">
                <div class="col s12 z-depth-5">
                    <movie-list :movies="movies"></movie-list>
                </div>
            </div>
            <div class="col s12 center" v-if="movies.length === 0 && !featuredMovie">
                No movies in Database.
                <router-link to="/addMovie">Add a movie</router-link>
            </div>
        </template>
        <template v-show="!loaded">
            <div class="container center">
                <div class="preloader-wrapper active pre-loader">
                    <div class="spinner-layer spinner-red-only">
                        <div class="circle-clipper left">
                            <div class="circle"></div>
                        </div>
                        <div class="gap-patch">
                            <div class="circle"></div>
                        </div>
                        <div class="circle-clipper right">
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
        data() {
            return {
                movies: [],
                loaded: false,
                viewMode: localStorage.getItem('viewMode') || 'grid',
                filter: {}
            }
        },
        created() {
            axios.get('/api/movies').then((res) => {
                this.movies = res.data.data;
                this.loaded = true;
            });
        },
        mounted() {
            setTimeout(() => {
                let genreNames = new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    prefetch: {
                        url: '/api/genreNames',
                        filter: function(list) {
                            return $.map(list, function(genreName) {
                                return { name: genreName }; });
                        }
                    }
                });
                genreNames.initialize();

                let input = $('#genre_filter');
                input.tagsinput({
                    typeaheadjs: {
                        name: 'genrenames',
                        displayKey: 'name',
                        valueKey: 'name',
                        source: genreNames.ttAdapter()
                    },
                    tagClass: 'custom-tag tag-center',
                });

                input.on('itemAdded', event => this.filter.genres = event.target.value.split(','));
                input.on('itemRemoved', event => this.filter.genres = event.target.value.split(','));


                let actorNames = new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    prefetch: {
                        url: '/api/actorNames',
                        filter: function(list) {
                            return $.map(list, function(actorName) {
                                return { name: actorName }; });
                        }
                    }
                });
                actorNames.initialize();

                input = $('#actor_filter');
                input.tagsinput({
                    typeaheadjs: {
                        name: 'actornames',
                        displayKey: 'name',
                        valueKey: 'name',
                        source: actorNames.ttAdapter()
                    },
                    tagClass: 'custom-tag tag-center',
                });

                input.on('itemAdded', event => this.filter.actors = event.target.value.split(','));
                input.on('itemRemoved', event => this.filter.actors = event.target.value.split(','));
            }, 0);
        },
        methods: {
            setViewMode(viewMode) {
                this.viewMode = viewMode;
                localStorage.setItem('viewMode', viewMode);
            },
        },
        computed: {
            featuredMovie() {
                return this.movies ? this.movies[0] : null;
            }
        },
        components: {
            MovieCard
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

    .toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
    }

    .view-mode {
        -webkit-box-shadow: inset 0 0 6px 2px rgba(156, 156, 156, 0.3);
        -moz-box-shadow: inset 0 0 6px 2px rgba(156, 156, 156, 0.3);
        box-shadow: inset 0 0 6px 2px rgba(156, 156, 156, 0.3);
        padding-left: 2px;
        padding-right: 2px;
        border: 1px solid #d1d1d1;
        border-radius: 12px;
        min-width: 71px;
    }

    .view-mode i {
        color: darkgrey;
        cursor: pointer;
    }

    .view-mode i.active {
        color: black;
    }

    .view-mode i:first-child {
        padding: 5px 1px 5px 5px;
    }

    .view-mode i:last-child {
        padding: 5px 5px 5px 1px;
        border-left: 1px solid #d1d1d1;
    }

    .filters {
        display: flex;
        align-items: center;
    }

    .genre-filter {
        display: flex;
        margin-left: 30px;
        align-items: center;
    }

    .genre-filter input {
        margin: 0;
    }

</style>