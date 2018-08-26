<template>
    <div style="margin-top: 20px;">
        <div class="container flex-box flex-justify-space-between" style="padding-bottom: 15px">
            <span class="pointer" @click="$router.push('/')"><i class="material-icons">arrow_back</i> Back</span>
            <div class="flex-box">
                <md-switch v-model="autocomplete" style="margin: 0;">Autocomplete from Movie DB</md-switch>
            </div>
        </div>
        <div v-show="!autocomplete">
            <add-movie-custom></add-movie-custom>
        </div>

        <div class="container hide-on-small-only z-depth-3" v-show="autocomplete">
            <add-move-tmdb></add-move-tmdb>


        </div>
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