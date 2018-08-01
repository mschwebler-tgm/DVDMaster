<template>
    <div class="container">
        <template v-if="movie && movie !== 404">
            <router-view :movie="movie"></router-view>
        </template>
        <template v-if="movie === 404">
            <div class="center" style="padding: 4em">
                <h5>Sorry, the movie you requested could not be found.</h5>
                <a href="#" @click="$root.$router.go(-1)">Go back</a>
            </div>
        </template>
        <template v-if="!movie">
            <div class="container center">
                <div class="preloader-wrapper active pre-loader">
                    <div class="spinner-layer spinner-red-only">
                        <div class="circle-clipper left">
                            <div class="circle"></div>
                            <div class="circle"></div>
                        </div>
                        <div class="gap-patch">
                            <div class="circle"></div>
                        </div>
                        <div class="circle-clipper right">
                            <div class="circle"></div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    export default {
        props: ['id'],
        created() {
            this.$store.dispatch('MOVIES_ACTION_GET_BY_ID', this.id);
        },
        computed: {
            movie() {
                return this.$store.getters.MOVIES_GET;
            }
        }
    }
</script>

<style scoped>

</style>