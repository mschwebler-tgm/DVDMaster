<template>
    <div style="margin-top: 20px;">
        <div class="container flex-box flex-justify-space-between" style="padding-bottom: 15px">
            <span class="pointer" @click="$router.push('/')"><i class="material-icons">arrow_back</i> Back</span>
        </div>
        <md-tabs>
            <md-tab id="tmdbSeries" class="nopad" md-label="Series (DB)">
                <add-series-tmdb></add-series-tmdb>
            </md-tab>
            <md-tab id="tmdbMovie" class="nopad" md-label="Movie (DB)">
                <add-move-tmdb></add-move-tmdb>
            </md-tab>
            <md-tab id="customMovie" class="nopad" md-label="Custom Movie">
                <add-movie-custom></add-movie-custom>
            </md-tab>
            <md-tab id="customSeries" class="nopad" md-label="Custom Series"></md-tab>
        </md-tabs>
    </div>
</template>

<script>
    export default {
        name: "AddMovie",
        data() {
            return {
                autocomplete: true
            }
        }
    }
</script>

<style scoped>
</style>