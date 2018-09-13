<template>
    <transition name="filters">
        <md-content class="md-elevation-2" v-show="show">
            <div class="pad filters mobile-column">
                <div class="tags">
                    <div class="mobile-column">
                        <div class="flex mobile-w-100">
                            <md-icon>person</md-icon>&nbsp;&nbsp;
                            <tags-input type="actors" image-key="profile_path" v-model="actors" v-on:input="onActors" style="flex: 1;"></tags-input>
                        </div>
                        <div class="item-chips">
                            <md-chip v-for="(item, index) in actors" :key="item.id" md-deletable @md-delete="actors.splice(index, 1); onActors()" class="item-chip">{{ item.name }}</md-chip>
                        </div>
                    </div>
                    <div class="mobile-column">
                        <div class="flex mobile-w-100">
                            <md-icon>bookmark</md-icon>&nbsp;&nbsp;
                            <tags-input type="genres" v-model="genres" v-on:input="onGenres"  style="flex: 1;"></tags-input>
                        </div>
                        <div class="item-chips">
                            <md-chip v-for="(item, index) in genres" :key="item.id" md-deletable @md-delete="genres.splice(index, 1); onGenres()" class="item-chip">{{ item.name }}</md-chip>
                        </div>
                    </div>
                </div>
                <div class="bools pad">
                    <bool-filters v-model="boolFilters" :type="type" v-on:input="onBoolFilters"></bool-filters>
                </div>
            </div>
        </md-content>
    </transition>
</template>

<script>
    import BoolFilters from './BoolFilters';

    export default {
        props: ['show', 'type'],
        data() {
            return {
                boolFilters: this.$store.getters[this.type + '_GET_FILTER'].bool || [],
                genres: [],
                actors: []
            }
        },
        created() {
            this.$store.watch(state => state[this.type.toLowerCase()].filter, () => {
                if (!_.isEmpty(this.boolFilters) || !_.isEmpty(this.genres) || !_.isEmpty(this.actors)) {
                    this.initFilters();
                    this.doSearch();
                }
            }, {deep: true});
        },
        methods: {
            initFilters() {
                this.boolFilters = this.$store.getters[this.type + '_GET_FILTER'].bool || [];
                this.genres = this.$store.getters[this.type + '_GET_FILTER'].genres || [];
                this.actors = this.$store.getters[this.type + '_GET_FILTER'].actors || [];
            },
            onBoolFilters() {
                this.filterUpdate('bool', this.boolFilters);
                this.doSearch();
            },
            onGenres() {
                this.filterUpdate('genres', this.genres);
                this.doSearch();
            },
            onActors() {
                this.filterUpdate('actors', this.actors);
                this.doSearch();
            },
            doSearch() {
                this.$store.dispatch(this.type + '_ACTION_SEARCH');
            },
            filterUpdate(type, data) {
                this.$store.commit(this.type + '_COMMIT_FILTER_UPDATE', {type, data});
            }
        },
        watch: {
            type() {
                this.$store.commit(this.type + '_COMMIT_PREVENT_SEARCH', true);
                this.initFilters();
                this.$store.commit(this.type + '_COMMIT_PREVENT_SEARCH', false);
            }
        },
        components: {
            BoolFilters
        }
    }
</script>

<style scoped>

    .filters {
        display: flex;
        flex-direction: row;
    }

    .tags {
        flex: 1;
    }

    .tags > div {
        display: flex;
        align-items: center;
    }

    .item-chips {
        display: flex;
        flex-wrap: wrap;
    }

    .item-chip {
        margin-left: 4px;
        margin-bottom: 4px;
    }

    .bools {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Animations */

    .filters-enter-active {
        max-height: 500px;
        -webkit-transition: max-height 1s;
        -moz-transition: max-height 1s;
        -ms-transition: max-height 1s;
        -o-transition: max-height 1s;
        transition: max-height 1s;
        overflow: hidden;
    }

    .filters-leave-active {
        max-height: 0;
        -webkit-transition: max-height .5s;
        -moz-transition: max-height .5s;
        -ms-transition: max-height .5s;
        -o-transition: max-height .5s;
        transition: max-height .5s;
        overflow: hidden;
    }

    .filters-leave {
        max-height: 500px;
    }

    .filters-enter {
        max-height: 0;
    }

</style>