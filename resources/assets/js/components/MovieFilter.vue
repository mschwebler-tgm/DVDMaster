<template>
    <div class="filters">
        <i class="material-icons">filter_list</i>
        <div class="genre-filter">
            Genres&nbsp;&nbsp;
            <genres-input v-if="showTaginputs" classes="custom-tag tag-center" @change="updateGenreFilter"></genres-input>
        </div>
        <div class="genre-filter">
            Actors&nbsp;&nbsp;
            <actors-input v-if="showTaginputs" classes="custom-tag tag-center" @change="updateActorFilter"></actors-input>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                filter: {},
                showTaginputs: false
            }
        },
        mounted() {
            setTimeout(() => this.showTaginputs = true, 1000);
        },
        methods: {
            updateGenreFilter(genres) {
                this.$store.commit('MOVIES_COMMIT_FILTER_UPDATE', {type: 'genres', data: this._pluckNames(genres)});
                this.$store.dispatch('MOVIES_ACTION_SEARCH');
            },
            updateActorFilter(actors) {
                this.$store.commit('MOVIES_COMMIT_FILTER_UPDATE', {type: 'actors', data: this._pluckNames(actors)});
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
        display: flex;
        align-items: center;
    }

    .genre-filter {
        display: flex;
        margin-left: 30px;
        align-items: center;
    }

    .genre-filter input {
        margin: 0;
    }
</style>