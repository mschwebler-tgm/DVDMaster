<template>
    <div>
        <div class="movie-row toContent" v-if="data && !searchingActive" v-for="content in data" @click="navigateToDetailPage($event, content)" :key="data.id">
            <content-list-item :content="content" :type="type"></content-list-item>
        </div>
        <loader v-if="searchingActive"></loader>
        <paginator :identifier="data.type + '-list'" :toDispatch="toDispatch"></paginator>
    </div>
</template>

<script>
    export default {
        props: ['type'],
        methods: {
            navigateToDetailPage(event, content) {
                if (event.target.classList.value.indexOf('toContent') !== -1 || this.$root.isMobile) {
                    this.$router.push(content.url);
                }
            }
        },
        computed: {
            data() {
                return this.type === 'movies' ?
                    this.$store.getters.MOVIES_GET_ALL :
                    this.$store.getters.SERIES_GET_ALL;
            },
            loading() {
                return this.type === 'movies' ?
                    this.$store.getters.MOVIES_GET_LOADING :
                    this.$store.getters.SERIES_GET_LOADING;
            },
            searchingActive() {
                return this.type === 'movies' ?
                    this.$store.getters.MOVIES_GET_SEARCHING :
                    this.$store.getters.SERIES_GET_SEARCHING;
            },
            toDispatch() {
                return this.type === 'movies' ?
                    'MOVIES_ACTION_GET_LOADNEXTPAGE' :
                    'SERIES_ACTION_GET_LOADNEXTPAGE';
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