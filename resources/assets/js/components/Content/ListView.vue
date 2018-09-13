<template>
    <div class="list-view">
        <div>
            <fade-transition :duration="200" name="fade" tag="div" v-if="data && !searchingActive" v-for="content in data"  :key="data.id">
                <div class="movie-row toContent" @click="navigateToDetailPage($event, content)">
                    <content-list-item :content="content" :type="type"></content-list-item>
                </div>
            </fade-transition>
        </div>

        <div v-if="!loading && !searchingActive && data.length === 0">
            <md-empty-state
                    md-icon="inbox"
                    md-label="No records found :("
                    md-description="Click below or use the shortcut STRG+Q to clear all filter selections and search terms.">
                <md-button class="md-primary md-raised" @click="$parent.clearFilters();">Clear filter</md-button>
            </md-empty-state>
        </div>

        <paginator :identifier="type + '-list'"
                   :toDispatch="paginateAction"
                   :loading="loading || searchingActive"
                   :nextPageUrl="nextPageUrl"
                   class="top"
                   style="position: absolute; top: 0; left: 0;"></paginator>
    </div>
</template>

<script>
    export default {
        props: ['type', 'data', 'loading', 'searchingActive', 'paginateAction', 'nextPageUrl'],
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

    .list-view {
        min-height: 121px;
        position: relative;
    }

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

    .top {
        position: absolute;
        top: 0;
    }

</style>