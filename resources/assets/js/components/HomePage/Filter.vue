<template>
    <transition name="filters">
        <md-content class="filters md-elevation-2" v-show="show">
            <div class="content-wrapper">
                <div class="content">
                    <div class="tag-filters">
                        <div class="flex flex-align-center">
                            <md-icon>bookmark</md-icon>&nbsp;&nbsp;<genres-input classes="custom-tag filter-tag" @change="updateGenreFilter"></genres-input> <span class="md-caption">Genres</span>
                        </div>
                        <div class="flex flex-align-center">
                            <md-icon>person</md-icon>&nbsp;&nbsp;<actors-input classes="custom-tag filter-tag" @change="updateActorFilter"></actors-input> <span class="md-caption">Actors</span>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <table>
                        <tr>
                            <td>
                                <span class="icon-filters" :class="{'icon-filter-active': (boolFilters.indexOf('borrowed') !== -1)}"
                                      @click="toggleFilter('borrowed')">
                                    <md-icon>import_export</md-icon>
                                    <md-tooltip md-direction="left">Borrowed</md-tooltip>
                                </span>
                            </td>
                            <td>
                                <span class="icon-filters" :class="{'icon-filter-active': (boolFilters.indexOf('blue_ray') !== -1)}"
                                      @click="toggleFilter('blue_ray')">
                                    <md-icon>album</md-icon>
                                    <md-tooltip md-direction="right">Blue Ray</md-tooltip>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="icon-filters" :class="{'icon-filter-active': (boolFilters.indexOf('true_story') !== -1)}"
                                      @click="toggleFilter('true_story')">
                                    <md-icon>event</md-icon>
                                    <md-tooltip md-direction="left">True story</md-tooltip>
                                </span>
                            </td>
                            <td>
                                <span class="icon-filters" :class="{'icon-filter-active': (boolFilters.indexOf('based_on_book') !== -1)}"
                                      @click="toggleFilter('based_on_book')">
                                    <md-icon>book</md-icon>
                                    <md-tooltip md-direction="right">Based on book</md-tooltip>
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="close" @click="$parent.toggleFilters()">
                    <md-icon>expand_less</md-icon>
                </div>
            </div>
        </md-content>
    </transition>
</template>

<script>
    export default {
        props: ['show'],
        data() {
            return {
                boolFilters: []
            }
        },
        methods: {
            toggleFilter(boolFilter) {
                if (this.boolFilters.indexOf(boolFilter) !== -1) {
                    this.boolFilters.splice(this.boolFilters.indexOf(boolFilter), 1);
                } else {
                    this.boolFilters.push(boolFilter);
                }
                this._updateBoolFilters();
            },
            updateGenreFilter(genres) {
                this.$store.commit('MOVIES_COMMIT_FILTER_UPDATE', {type: 'genres', data: this._pluckNames(genres)});
                this.$store.dispatch('MOVIES_ACTION_SEARCH');
            },
            updateActorFilter(actors) {
                this.$store.commit('MOVIES_COMMIT_FILTER_UPDATE', {type: 'actors', data: this._pluckNames(actors)});
                this.$store.dispatch('MOVIES_ACTION_SEARCH');
            },
            _updateBoolFilters() {
                this.$store.commit('MOVIES_COMMIT_FILTER_UPDATE', {type: 'bool', data: this.boolFilters});
                this.$store.dispatch('MOVIES_ACTION_SEARCH');
            },
            _pluckNames(dataArray) {
                let names = [];
                for (let data of dataArray) {
                    names.push(data.name);
                }
                return names;
            }
        }
    }
</script>

<style scoped>

    .filters {
        height: 100px;
    }

    .close {
        margin-left: auto;
        padding: 10px;
        cursor: pointer;
    }

    .content-wrapper {
        display: flex;
        height: 100%;
    }
    .content {
        padding: 15px 15px 15px 30px;
        display: flex;
        align-items: center;
        height: 100%;
    }

    .content:first-child {
        flex: 2;
    }

    .content:nth-child(2) {
        flex: 1;
        flex-wrap: wrap;
    }

    .tag-filters {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: space-around;
        height: 100%;
    }

    .tag-filters span {
        -webkit-transition: all 1s;
        -moz-transition: all 1s;
        -ms-transition: all 1s;
        -o-transition: all 1s;
        transition: all 1s;
    }

    .icon-filters {
        padding: 5px;
        cursor: pointer;
    }

    .icon-filters i {
        /*color: var(--md-theme-demo-light-text-accent-on-background-variant, rgba(0,0,0,.24)) !important;*/
    }

    .icon-filters:hover i {
        color: var(--md-theme-default-accent, rgba(0,0,0,0.54)) !important;
        -webkit-transition: color .2s cubic-bezier(0.4, 0, 0.2, 1);
        -moz-transition: color .2s cubic-bezier(0.4, 0, 0.2, 1);
        -ms-transition: color .2s cubic-bezier(0.4, 0, 0.2, 1);
        -o-transition: color .2s cubic-bezier(0.4, 0, 0.2, 1);
        transition: color .2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .icon-filters.icon-filter-active i {
        color: var(--md-theme-default-accent, rgba(0,0,0,0.54)) !important;
    }

    /* Animations */

    .filters-enter-active {
        height: 100px;
        -webkit-transition: height .5s;
        -moz-transition: height .5s;
        -ms-transition: height .5s;
        -o-transition: height .5s;
        transition: height .5s;
        overflow: hidden;
    }

    .filters-leave-active {
        height: 0;
        -webkit-transition: height .5s;
        -moz-transition: height .5s;
        -ms-transition: height .5s;
        -o-transition: height .5s;
        transition: height .5s;
        overflow: hidden;
    }

    .filters-leave {

    }

    .filters-enter {
        height: 0;
    }

</style>