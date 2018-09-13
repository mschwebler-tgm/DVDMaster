<template>
    <div class="md-elevation-1">
        <div class="header">
            <md-toolbar class="md-accent">
                <h3 class="md-title toolbar-label">{{ label }}</h3>
                <div class="series-toolbar">
                    <md-button class="md-icon-button" @click="toggleFilters">
                        <md-icon>filter_list</md-icon>
                    </md-button>
                    <md-button class="md-dense md-primary" @click="clearFilters">Clear filter</md-button>
                </div>
                <div style="width: 127px;"></div> <!-- compensate absolute view toggle element -->
                <div class="viewToggle">
                    <md-switch v-model="listView" class="desktop-only"><span class="desktop-only">{{ listView ? 'Listview' : 'Gridview' }}</span></md-switch>
                </div>
            </md-toolbar>
        </div>
        <home-filter :show="showFilters" :type="type"></home-filter>
        <div class="pad no-pad-mobile" style="min-height: 731px;">
            <content-list v-show="listView"
                          :type="type"
                          :data="content"
                          :loading="loading"
                          :searchingActive="searchingActive"
                          :nextPageUrl="nextPageUrl"
                          :paginateAction="actions.paginateAction"></content-list>
            <content-cards v-show="!listView"
                           :type="type"
                           :data="content"
                           :loading="loading"
                           :searchingActive="searchingActive"
                           :nextPageUrl="nextPageUrl"
                           :paginateAction="actions.paginateAction"></content-cards>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                type: this.$route.name,  // MOVIES || SERIES
                listView: localStorage.getItem('listView') === 'true',
                showFilters: localStorage.getItem('showFilters_home') === 'true',
                getters: {
                    all: this.$route.name + '_GET_ALL',  // MOVIES_GET_ALL
                    loading: this.$route.name + '_GET_LOADING',  // MOVIES_GET_LOADING
                    searchingActive: this.$route.name + '_GET_SEARCHING',  // MOVIES_GET_SEARCHING
                    nextPageUrl: this.$route.name + '_GET_NEXT_PAGE_URL',  // MOVIES_GET_NEXT_PAGE_URL
                    filters: this.$route.name + '_GET_FILTER', // MOVIES_GET_FILTER
                },
                actions: {
                    firstPage: this.$route.name + '_ACTION_GET_FIRSTPAGE',  // MOVIES_ACTION_GET_FIRSTPAGE,
                    paginateAction: this.$route.name + '_ACTION_GET_LOADNEXTPAGE'  // MOVIES_ACTION_GET_LOADNEXTPAGE
                }
            }
        },
        created() {
            this.initModule(this.$route.name);
            $(document).bind('keydown', 'ctrl+q', () => {
                this.clearFilters();
                this.$store.dispatch(this.type + '_ACTION_SEARCH');
            })
        },
        methods: {
            initModule(module) {
                this.type = module;
                this.initGetters(module);
                this.initActions(module);
                if (this.$store.getters[this.getters.all].length === 0 && this.isFilterEmpty()) {
                    this.$store.dispatch(this.actions.firstPage);
                }
            },
            initGetters(module) {
                this.getters.all = module + '_GET_ALL';  // MOVIES_GET_ALL
                this.getters.filters = module + '_GET_FILTER';  // MOVIES_GET_FILTER
                this.getters.loading = module + '_GET_LOADING';  // MOVIES_GET_LOADING
                this.getters.nextPageUrl = module + '_GET_NEXT_PAGE_URL';  // MOVIES_GET_SEARCHING
                this.getters.searchingActive = module + '_GET_SEARCHING';  // MOVIES_GET_SEARCHING
            },
            initActions(module) {
                this.actions.firstPage = module + '_ACTION_GET_FIRSTPAGE';  // MOVIES_ACTION_GET_FIRSTPAGE
                this.actions.paginateAction = module + '_ACTION_GET_LOADNEXTPAGE';  // MOVIES_ACTION_GET_LOADNEXTPAGE
            },
            toggleFilters() {
                this.showFilters = !this.showFilters;
                localStorage.setItem('showFilters_home', this.showFilters ? 'true' : 'false');
            },
            clearFilters() {
                this.$store.commit(this.type + '_COMMIT_CLEAR_FILTER')
            },
            isFilterEmpty() {
                return _.isEmpty(this.$store.getters[this.getters.filters].bool) &&
                       _.isEmpty(this.$store.getters[this.getters.filters].genres) &&
                       _.isEmpty(this.$store.getters[this.getters.filters].actors) &&
                       _.isEmpty(this.$store.getters[this.getters.filters].title);
            }
        },
        watch: {
            listView(val) {
                localStorage.setItem('listView', val);
            },
            '$route.name'(type) {
                this.initModule(type);
            }
        },
        computed: {
            content() {
                return this.$store.getters[this.getters.all];
            },
            loading() {
                return this.$store.getters[this.getters.loading];
            },
            searchingActive() {
                return this.$store.getters[this.getters.searchingActive];
            },
            nextPageUrl() {
                return this.$store.getters[this.getters.nextPageUrl];
            },
            label() {
                return this.type.charAt(0).toUpperCase() + this.type.slice(1).toLowerCase();
            }
        }
    }
</script>

<style scoped>
    .header {
        margin-top: 5px;
    }

    .series-toolbar {
        padding-left: 10px;
        padding-right: 10px;
        margin-left: 10px;
        margin-right: 10px;
        display: flex;
        align-items: center;
        background-color: rgba(0, 0, 0, 0.13);
    }

    .viewToggle {
        padding: 6px 6px 6px 16px;
        background: #fff;
        position: absolute;
        top: 0;
        right: 0;
        color: var(--md-theme-default-text-primary-on-background, rgba(0, 0, 0, 0.87));
        width: 143px;
        display: flex;
        align-items: center;
    }

    .toolbar-label {
        width: 100px;
    }

    @media only screen and (max-width: 960px) {
        .viewToggle {
            height: 48px;
        }
    }

    @media only screen and (max-width: 600px) {
        .viewToggle {
            height: 56px;
            width: 62px;
        }
        .toolbar-label {
            width: auto;
        }
    }
</style>