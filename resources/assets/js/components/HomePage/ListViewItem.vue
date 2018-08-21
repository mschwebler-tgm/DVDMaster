<template>
    <div class="flex flex-align-center toMovie" v-if="movie">
        <div class="flex flex-align-center flex-justify-space-between toMovie" style="flex: 1;">
            <div class="flex flex-align-center title toMovie">
                <img class="cover toMovie" :src="$root.getImagePath(movie.poster_path, 'w92')" width="92" height="138">
                <div>
                    <span class="md-title">{{ movie.title }}</span>
                    <div style="margin-bottom: 8px"></div>
                    <template v-for="genre in getGenreNames(movie)">
                        <md-chip class="genre-chip">{{ genre }}</md-chip>
                    </template>
                </div>
            </div>
            <div class="hints toMovie">
                <span class="md-title toMovie" style="opacity: 0;">A</span>
                <div class="flex toMovie">
                    <div v-if="rentedBy">
                        <md-icon>import_export</md-icon>
                        <md-tooltip md-direction="bottom">Borrowed by {{ rentedBy.name }}</md-tooltip>
                    </div>
                    <div v-if="isBlueRay">
                        <md-icon>album</md-icon>
                        <md-tooltip md-direction="bottom">Blue Ray</md-tooltip>
                    </div>
                    <div v-if="isTrueStory">
                        <md-icon>event</md-icon>
                        <md-tooltip md-direction="bottom">True story</md-tooltip>
                    </div>
                    <div v-if="isBasedOnBook">
                        <md-icon>book</md-icon>
                        <md-tooltip md-direction="bottom">Based on book</md-tooltip>
                    </div>
                </div>
            </div>
            <div class="actors-container flex flex-align-center toMovie">
                <div class="actors">
                    <template v-for="actor in getActorNames(movie)">
                        <span class="md-caption pointer">{{ actor }}</span>
                        <br>
                    </template>
                </div>
                <div>
                    <md-icon :style="'opacity: ' + (movie.actors.length !== 0 ? 1 : 0)">person</md-icon>
                    <md-tooltip md-direction="right">Actors</md-tooltip>
                </div>
            </div>
        </div>
        <movie-rating :movie="movie" @newCustomRating="updateRating(movie, $event)"></movie-rating>
    </div>
</template>

<script>
    export default {
        props: ['movie'],
        methods: {
            getGenreNames(movie) {
                let names = [];
                for (let i = 0; movie.genres && i < movie.genres.length && names.length < 3; i++) {
                    names.push(movie.genres[i].name);
                }
                return names;
            },
            getActorNames(movie) {
                let names = [];
                for (let i = 0; movie.actors && i < movie.actors.length && names.length < 4; i++) {
                    names.push(movie.actors[i].name);
                }
                return names;
            },
            updateRating(movie, rating) {
                axios.post('/api/movie/' + movie.id + '/rate', {rating});
            }
        },
        computed: {
            rentedBy() {
                if (this.movie.rented_by.length > 0) {
                    return this.movie.rented_by[0];
                }
                return null;
            },
            isBlueRay() {
                return !!this.movie.blue_ray;
            },
            isTrueStory() {
                return !!this.movie.true_story;
            },
            isBasedOnBook() {
                return !!this.movie.based_on_book;
            }
        }
    }
</script>

<style scoped>
    span {
        cursor: text;
    }
    
    .title {
        width: 400px;
    }

    .cover {
        margin-right: 20px;
        height: 130px;
        width: 92px;
    }

    .genre-chip {
        margin-bottom: 4px;
    }

    .actors-container {
        padding-right: 11%;
    }

    .actors-container i {
        color: var(--md-theme-default-divider, rgba(0,0,0,0.12)) !important;
    }

    .actors {
        padding-right: 10px;
        margin-right: 10px;
        border-right: 1px solid var(--md-theme-default-divider, rgba(0,0,0,0.12));
        width: 170px
    }

    .actors span {
        float: right;
        white-space: nowrap
    }

    .actors span:hover {
        color: rgba(0, 0, 0, 0.64);
    }

    .hints {
        padding-left: 15px;
        padding-right: 15px;
        flex: 1;
    }

    .hints i {
        color: var(--md-theme-default-divider, rgba(0,0,0,0.12)) !important;
        padding-left: 15px;
        padding-right: 15px;
    }

    .hints i:hover {
        color: var(--md-theme-default-text-primary-on-background, rgba(0,0,0,0.87)) !important;
    }

    @media only screen and (max-width: 960px) {
        .actors-container {
            padding-right: 6%;
        }
    }

    @media only screen and (max-width: 850px) {
        .actors-container {
            display: none;
        }
    }

    @media only screen and (max-width: 1640px) {
        .hints {
            display: none;
        }
        .title {
            width: auto;
        }
    }

    @media only screen and (min-width: 961px) and (max-width: 1430px) {
        .actors-container {
            display: none;
        }
        .hints {
            display: block;
            margin-right: 13%;
            flex: initial;
        }
    }

    @media only screen and (min-width: 961px) and (max-width: 1230px) {
        .hints {
            display: block;
            margin-right: 6%;
        }
    }

    @media only screen and (min-width: 961px) and (max-width: 1100px) {
        .hints {
            display: none;
        }
    }
</style>