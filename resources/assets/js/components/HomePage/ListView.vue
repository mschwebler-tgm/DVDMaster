<template>
    <div>
        <div class="movie-row toMovie" v-if="movies && !searchingActive" v-for="movie in movies" @click="navigateToMovie($event, movie)" :key="movie.id">
            <movie-list-item :movie="movie"></movie-list-item>
        </div>
        <loader v-if="searchingActive"></loader>
        <paginator identifier="movie-list" toDispatch="MOVIES_ACTION_GET_LOADNEXTPAGE"></paginator>
    </div>
</template>

<script>
    export default {
        props: ['movies'],
        methods: {
            navigateToMovie(event, movie) {
                if (event.target.classList.value.indexOf('toMovie') !== -1 || this.$root.isMobile) {
                    this.$router.push('/movie/' + movie.id);
                }
            }
        },
        computed: {
            loading() {
                return this.$store.getters.MOVIES_GET_LOADING;
            },
            searchingActive() {
                return this.$store.getters.MOVIES_GET_SEARCHING;
            }
        }
    }
</script>

<style scoped>

    .movie-row {
        padding: 10px;
        border-top: 1px solid var(--md-theme-default-divider, rgba(0,0,0,0.12));
        transition: .3s cubic-bezier(.4,0,.2,1);
        transition-property: background-color,font-weight;
        will-change: background-color,font-weight;
        cursor: pointer;
    }

    .movie-row:hover {
        background-color: var(--md-theme-default-highlight-on-background, rgba(0,0,0,0.08));
    }

    .movie-row:last-child {
        border-bottom: 0;
    }
</style>