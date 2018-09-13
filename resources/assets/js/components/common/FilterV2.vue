<template>
    <transition name="filters">
        <md-content class="md-elevation-2" v-show="show">
            <div class="pad filters">
                <div class="tags">
                    <tags-input type="actors" image-key="profile_path" v-model="actors"></tags-input>
                    <md-chip v-for="(item, index) in actors" :key="item.id" md-deletable @md-delete="selectedItems.splice(index, 1)" class="item-chip">{{ item.name }}</md-chip>
                    <tags-input type="genres" v-model="genres"></tags-input>
                    <md-chip v-for="(item, index) in genres" :key="item.id" md-deletable @md-delete="selectedItems.splice(index, 1)" class="item-chip">{{ item.name }}</md-chip>
                </div>
                <div class="bools pad">
                    <bool-filters v-model="boolFilters" :type="type"></bool-filters>
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
        watch: {
            boolFilters() {
                this.$store.commit(this.type + '_COMMIT_FILTER_UPDATE', {type: 'bool', data: this.boolFilters});
                this.$store.dispatch(this.type + '_ACTION_SEARCH');
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

    .bools {
        display: flex;
        justify-content: center;
        align-items: center;
    }

</style>