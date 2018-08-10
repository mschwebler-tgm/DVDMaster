<template>
    <div>
        <!--<div class="header">-->
            <!--Title-->
        <!--</div>-->
        <div class="sk-double-bounce" v-show="movies.length === 0">
            <div class="sk-child sk-double-bounce1"></div>
            <div class="sk-child sk-double-bounce2"></div>
        </div>
        <div class="movie-row toMovie" v-if="movies" v-for="movie in movies" @click="navigateToMovie($event, movie)" :key="movie.id">
            <movie-list-item :movie="movie"></movie-list-item>
        </div>
        <div class="sk-wave" v-else-if="movies.length === 0">
            <div class="sk-rect sk-rect1"></div>
            <div class="sk-rect sk-rect2"></div>
            <div class="sk-rect sk-rect3"></div>
            <div class="sk-rect sk-rect4"></div>
            <div class="sk-rect sk-rect5"></div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['movies'],
        created() {
            console.log(this.movies);
        },
        methods: {
            navigateToMovie(event, movie) {
                if (event.target.classList.value.indexOf('toMovie') !== -1) {
                    this.$router.push('/movie/' + movie.id);
                }
            }
        }
    }
</script>

<style scoped>
    .movie-row {
        padding: 10px;
        border-bottom: 1px solid var(--md-theme-default-divider, rgba(0,0,0,0.12));
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