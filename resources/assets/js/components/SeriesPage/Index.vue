<template>
    <div class="md-elevation-1">
        <div class="header">
            <md-toolbar class="md-accent">
                <h3 class="md-title">Series</h3>
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
        <home-filter :show="showFilters"></home-filter>
        <div class="pad no-pad-mobile">
            <content-list v-show="listView" type="series"></content-list>
            <content-cards v-show="!listView" type="series"></content-cards>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                listView: localStorage.getItem('listView') === 'true',
                showFilters: localStorage.getItem('showFilters_home') === 'true'
            }
        },
        created() {
            if (this.$store.getters.SERIES_GET_ALL.length === 0) {
                this.$store.dispatch('SERIES_ACTION_GET_FIRSTPAGE');
            }
        },
        methods: {
            toggleFilters() {
                this.showFilters = !this.showFilters;
                localStorage.setItem('showFilters_home', this.showFilters ? 'true' : 'false');
            }
        },
        watch: {
            listView(val) {
                localStorage.setItem('series_listView', val);
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