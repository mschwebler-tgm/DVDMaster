<template>
    <div class="input-field right" style="width: 400px; background-color: #CC444B;">
        <input id="search" type="search" style="height: 64px !important" v-model="query" @keyup.enter="search">
        <label class="label-icon" for="search"><i class="material-icons">search</i></label>
        <i class="material-icons" @click="reset" :class="{'clear-active': query !== ''}">close</i>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                query: ''
            }
        },
        methods: {
            search() {
                this.$store.commit('MOVIES_COMMIT_FILTER_UPDATE', {type: 'title', data: this.query});
                this.$store.dispatch('MOVIES_ACTION_SEARCH');
            },
            reset() {
                this.query = '';
                this.$store.commit('MOVIES_COMMIT_FILTER_UPDATE', {type: 'title', data: this.query});
                this.$store.dispatch('MOVIES_ACTION_SEARCH');
            }
        }
    }
</script>

<style scoped>
    #search {
        color: white;
    }
    #search:focus {
        color: black;
    }
    #search:not(:focus) ~ .clear-active {
        color: rgba(255,255,255,0.7) !important;
    }
</style>