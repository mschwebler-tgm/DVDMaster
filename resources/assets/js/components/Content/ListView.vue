<template>
    <div>
        <div class="movie-row toContent" v-if="data && !searchingActive" v-for="content in data" @click="navigateToDetailPage($event, content)" :key="data.id">
            <content-list-item :content="content" :type="type"></content-list-item>
        </div>
        <loader v-if="searchingActive"></loader>
        <paginator :identifier="type + '-list'" :toDispatch="paginateAction"></paginator>
    </div>
</template>

<script>
    export default {
        props: ['type', 'data', 'loading', 'searchingActive', 'paginateAction'],
        methods: {
            navigateToDetailPage(event, content) {
                if (event.target.classList.value.indexOf('toContent') !== -1 || this.$root.isMobile) {
                    this.$router.push(content.url);
                }
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