<template>
    <div>
        <div class="series-row toMovie" v-if="series && !searchingActive" v-for="serie in series" @click="navigateToSeries($event, serie)" :key="serie.id">
            <series-list-item :series="serie"></series-list-item>
        </div>
        <loader v-if="searchingActive"></loader>
        <paginator identifier="series-list" toDispatch="SERIES_ACTION_GET_LOADNEXTPAGE"></paginator>
    </div>
</template>

<script>
    export default {
        methods: {
            navigateToSeries(event, movie) {
                if (event.target.classList.value.indexOf('toMovie') !== -1 || this.$root.isMobile) {
                    this.$router.push('/movie/' + movie.id);
                }
            }
        },
        computed: {
            series() {
                return this.$store.getters.SERIES_GET_ALL;
            },
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
    .series-row {
        padding: 10px;
        border-top: 1px solid var(--md-theme-default-divider, rgba(0,0,0,0.12));
        transition: .3s cubic-bezier(.4,0,.2,1);
        transition-property: background-color,font-weight;
        will-change: background-color,font-weight;
        cursor: pointer;
    }

    .series-row:hover {
        background-color: var(--md-theme-default-highlight-on-background, rgba(0,0,0,0.08));
    }

    .series-row:last-child {
        border-bottom: 0;
    }
</style>