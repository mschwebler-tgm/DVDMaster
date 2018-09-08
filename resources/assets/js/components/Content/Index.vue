<template>
    <div class="md-elevation-1">
        <div class="header">
            <md-toolbar class="md-accent">
                <h3 class="md-title">{{ heading }}</h3>
                <div class="series-toolbar">
                    <div class="pointer" @click="toggleFilters">
                        <md-icon >filter_list</md-icon>
                    </div>
                </div>
                <div style="width: 127px;"></div> <!-- compensate absolute view toggle element -->
                <div class="viewToggle">
                    <md-switch v-model="listView">{{ listView ? 'Listview' : 'Gridview' }}
                    </md-switch>
                </div>
            </md-toolbar>
        </div>
        <home-filter :show="showFilters" :type="type"></home-filter>
        <div class="pad no-pad-mobile">
            <content-list v-show="listView"
                          :type="type"
                          :data="content"
                          :loading="loading"
                          :searchingActive="searchingActive"
                          :paginateAction="actions.paginateAction"></content-list>
            <content-cards v-show="!listView"
                           :type="type"
                           :data="content"
                           :loading="loading"
                           :searchingActive="searchingActive"
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
                },
                actions: {
                    firstPage: this.$route.name + '_ACTION_GET_FIRSTPAGE',  // MOVIES_ACTION_GET_FIRSTPAGE,
                    paginateAction: this.$route.name + '_ACTION_GET_LOADNEXTPAGE'  // MOVIES_ACTION_GET_LOADNEXTPAGE
                },
                heading: ''
            }
        },
        created() {
            this.initModule(this.$route.name);
            this.heading = this.type.charAt(0).toUpperCase() + this.type.slice(1).toLowerCase();
        },
        methods: {
            initModule(module) {
                this.initGetters(module);
                this.initActions(module);
                if (this.$store.getters[this.getters.all].length === 0) {
                    this.$store.dispatch(this.actions.firstPage);
                }
            },
            initGetters(module) {
                this.getters.all = module + '_GET_ALL';  // MOVIES_GET_ALL
                this.getters.loading = module + '_GET_LOADING';  // MOVIES_GET_LOADING
                this.getters.searchingActive = module + '_GET_SEARCHING';  // MOVIES_GET_SEARCHING
            },
            initActions(module) {
                this.actions.firstPage = module + '_ACTION_GET_FIRSTPAGE';  // MOVIES_ACTION_GET_FIRSTPAGE
                this.actions.paginateAction = module + '_ACTION_GET_LOADNEXTPAGE';  // MOVIES_ACTION_GET_LOADNEXTPAGE
            },
            toggleFilters() {
                this.showFilters = !this.showFilters;
                localStorage.setItem('showFilters_home', this.showFilters ? 'true' : 'false');
            }
        },
        watch: {
            listView(val) {
                localStorage.setItem('listView', val);
            },
            '$route.name'(type) {
                this.initGetters(type);
                this.initActions(type);
                if (this.$store.getters[this.getters.all].length === 0) {
                    this.$store.dispatch(this.actions.firstPage);
                }
                this.type = this.$route.name;
                this.heading = this.type.charAt(0).toUpperCase() + this.type.slice(1).toLowerCase();
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
            }
        }
    }
</script>

<style scoped>
    .header {
        margin-top: 5px;
    }

    .series-toolbar {
        padding-left: 20px;
        padding-right: 20px;
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

    @media only screen and (max-width: 960px) {
        .viewToggle {
            height: 48px;
        }
    }

    @media only screen and (max-width: 600px) {
        .viewToggle {
            height: 56px;
        }
    }
</style>