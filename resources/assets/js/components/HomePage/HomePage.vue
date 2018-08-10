<template>
    <div>
        <div class="md-elevation-1 pad">
            <div class="flex flex-justify-space-between flex-align-end">
                <span class="md-display-3">Movies</span>
                <md-switch v-model="listView">Listview</md-switch>
            </div>
            <md-divider></md-divider>
            <movie-list v-if="listView" :movies="movies"></movie-list>
            <movie-cards v-else></movie-cards>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                listView: localStorage.getItem('listView') === 'true'
            }
        },
        created() {
            this.$store.dispatch('MOVIES_ACTION_GET_FIRSTPAGE').then(() => {
                this.loaded = true;
            });
        },
        computed: {
            movies() {
                return this.$store.getters.MOVIES_GET_ALL;
            }
        },
        watch: {
            listView(val) {
                localStorage.setItem('listView', val);
            }
        }
    }
</script>

<style scoped>

</style>