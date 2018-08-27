<template>
    <div>
        <div class="movie-cards" ref="cardsContainer">
            <div v-for="movie in movies" style="flex: 1">
                <div class="image" :style="'background-image: url(' + $root.getImagePath(movie.poster_path, 'w154') + ')'">
                    <div class="darkener pointer pad" @click="$router.push('/movie/' + movie.id)">
                        <span class="md-headline">{{ movie.title }}</span>
                        <movie-rating :movie="movie" :starSize="20"></movie-rating>
                    </div>
                </div>
            </div>
            <div v-if="placeholders" v-for="n in placeholders" style="flex: 1"></div>
        </div>
        <paginator identifier="movie-cards" toDispatch="MOVIES_ACTION_GET_LOADNEXTPAGE"></paginator>
    </div>
</template>

<script>
    import MovieCard from "./MovieCard";

    export default {
        data() {
            return {
                viewMode: localStorage.getItem('viewMode') || 'grid',
                placeholders: false
            }
        },
        created() {
            this.$store.dispatch('MOVIES_ACTION_GET_FIRSTPAGE').then(() => {
                this.loaded = true;
            });
        },
        mounted() {
            let update;
            window.onresize = () => {
                clearTimeout(update);
                update = setTimeout(this.updatePlaceholders, 100);
            }
        },
        methods: {
            setViewMode(viewMode) {
                this.viewMode = viewMode;
                localStorage.setItem('viewMode', viewMode);
            },
            updatePlaceholders() {
                let moviesAmount = this.$store.getters.MOVIES_GET_ALL.length;
                let perRow = Math.floor(this.$refs.cardsContainer.offsetWidth / 154);
                this.placeholders = perRow === 0 || moviesAmount % perRow === 0 ? 0 : perRow - (moviesAmount % perRow);
            }
        },
        watch: {
            movies() {
                if (this.$store.getters.MOVIES_GET_ALL.length > 0) {
                    this.updatePlaceholders();
                }
            }
        },
        computed: {
            movies() {
                return this.$store.getters.MOVIES_GET_ALL;
            },
            loaded: {
                get() {
                    return this.$store.getters.MOVIES_GET_ALL.length > 0;
                },
                set() {}
            }
        },
        components: {
            MovieCard
        }
    }
</script>

<style lang="scss" scoped>
    @import "~vue-material/src/components/MdAnimation/variables";
    @import "~vue-material/dist/theme/engine";

    .movie-cards {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }

    .image {
        min-width: 154px;
        padding-bottom: 142%;
        /*height: 219px;*/
        position: relative;
        -webkit-background-size: 100% 100%;
        background-size: 100% 100%;
    }

    .darkener {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        opacity: 0;
        -webkit-transition: opacity .2s $md-transition-stand-timing;
        -moz-transition: opacity .2s $md-transition-stand-timing;
        -ms-transition: opacity .2s $md-transition-stand-timing;
        -o-transition: opacity .2s $md-transition-stand-timing;
        transition: opacity .2s $md-transition-stand-timing;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
    }

    .darkener span {
        color: white;
        text-align: center;
    }

    .image:hover .darkener {
        opacity: 1;
    }
</style>