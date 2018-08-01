<template>
    <div class="input-field right" style="width: 400px; background-color: #CC444B;">
        <input id="search" type="search" style="height: 64px !important" v-model="query" @keyup.enter="search">
        <label class="label-icon" for="search"><i class="material-icons">search</i></label>
        <i class="material-icons">close</i>
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
                axios.get('/api/customSearch/movies?query=' + this.query);
            }
        }
    }
</script>

<style scoped>

</style>