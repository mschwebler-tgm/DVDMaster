<template>
    <transition name="filters">
        <md-content class="md-elevation-2" v-show="show">
            <div class="pad filters">
                <div class="tags">
                    <div>
                        <tags-input type="actors" image-key="profile_path" v-model="actors" v-on:input="onActors"></tags-input>
                        <div class="item-chips">
                            <md-chip v-for="(item, index) in actors" :key="item.id" md-deletable @md-delete="actors.splice(index, 1); onActors()" class="item-chip">{{ item.name }}</md-chip>
                        </div>
                    </div>
                    <div>
                        <tags-input type="genres" v-model="genres" v-on:input="onGenres"></tags-input>
                        <md-chip v-for="(item, index) in genres" :key="item.id" md-deletable @md-delete="genres.splice(index, 1); onGenres()" class="item-chip">{{ item.name }}</md-chip>
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
        methods: {
            initFilters() {
                this.boolFilters = this.$store.getters[this.type + '_GET_FILTER'].bool || [];
                this.genres = this.$store.getters[this.type + '_GET_FILTER'].genres || [];
                this.actors = this.$store.getters[this.type + '_GET_FILTER'].actors || [];
            },
            onBoolFilters() {
                this.$store.commit(this.type + '_COMMIT_FILTER_UPDATE', {type: 'bool', data: this.boolFilters});
                this.$store.dispatch(this.type + '_ACTION_SEARCH');
            },
            onGenres() {
                this.$store.commit(this.type + '_COMMIT_FILTER_UPDATE', {type: 'genres', data: this.genres});
                this.$store.dispatch(this.type + '_ACTION_SEARCH');
            },
            onActors() {
                this.$store.commit(this.type + '_COMMIT_FILTER_UPDATE', {type: 'actors', data: this.actors});
                this.$store.dispatch(this.type + '_ACTION_SEARCH');
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

</style>