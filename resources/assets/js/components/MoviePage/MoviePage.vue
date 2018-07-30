<template>
    <div class="container">
        <template v-if="movie">
            <router-view :movie="movie"></router-view>
        </template>
        <template v-if="!loading && !movie">
            <div class="center" style="padding: 4em">
                <h5>Sorry, the movie you requested could not be found.</h5>
                <a href="#" @click="$root.$router.go(-1)">Go back</a>
            </div>
        </template>
        <template v-if="loading">
            <div class="container center">
                <div class="preloader-wrapper active pre-loader">
                    <div class="spinner-layer spinner-red-only">
                        <div class="circle-clipper left">
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
        data() {
            return {
                loading: true,
                movie: null,
            }
        },
        created() {
            axios.get('/api/movie/' + this.id).then(res => {
                this.movie = res.data;
                this.loading = false;
            }).catch(err => {
                this.loading = false;
                M.toast({html: 'Error while loading movie', classes: 'complete-toast'});
            });
        },
    }
</script>

<style scoped>

</style>