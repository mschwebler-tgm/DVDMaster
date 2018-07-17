<template>
    <div class="container">
        <div class="row z-depth-5 " v-if="!loading && movie">
            <div class="col s12 no-padding">
                <div class="backdrop-image"
                     :style="{ 'backgroundImage': 'url(' + $root.getImagePath(movie.backdrop_path, 'w1280') + ')'}">
                    <div class="poster-image"
                         :style="{ 'backgroundImage': 'url(' + $root.getImagePath(movie.poster_path, 'w185') + ')' }"></div>
                    <div class="movie-title">
                        <span>{{ movie.title }}</span>
                    </div>
                </div>
            </div>
            <div class="col s12">
                <div class="movie-header">
                    <div class="poster-spacer"></div>
                    <div class="tool-bar">
                        <div><i class="material-icons">edit</i> Edit</div>
                        <div><i class="material-icons">assignment_ind</i> Borrow</div>
                        <div><i class="material-icons">movie</i> Just seen</div>
                        <div style="margin-left: auto; margin-right: 0;" @click="deleteMovie"><i class="material-icons">delete</i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12">
            </div>
        </div>
        <div v-else-if="!loading && !movie">
            <div class="center" style="padding: 4em">
                <h5>Sorry, the movie you requested could not be found.</h5>
                <a href="#" @click="$root.$router.go(-1)">Go back</a>
            </div>
        </div>
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
                movie: null
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
        methods: {
            deleteMovie() {
                axios.get('/api/movie/' + this.id + '/delete').then(() => {
                    M.toast({html: 'Movie deleted', classes: 'complete-toast'});
                    this.$root.$router.go(-1);
                }).catch(() => {
                    M.toast({html: 'Error while deleting movie', classes: 'complete-toast'});
                });
            }
        }
    }
</script>

<style scoped>

    .backdrop-image {
        height: 340px;
        width: 100%;
        position: relative;
        background-size: cover;
        background-position: center center;
        display: flex;
        padding: 20px;
    }

    .poster-image {
        position: absolute;
        left: 10%;
        top: 150px;
        width: 185px;
        height: 278px;
        background-size: cover;
        background-position: center center;
        overflow: hidden;
        border: 3px ridge #e0e0e0;
    }

    .movie-header {
        display: flex;
        width: 100%;
    }

    .poster-spacer {
        width: calc(10% + 185px + 10px);
    }

    .movie-title {
        color: white;
        flex: 1;
        position: absolute;
        left: calc(10% + 185px + 20px);
        align-self: flex-end;
    }

    .movie-title span {
        font-size: 40px;
        margin: 0;
        background-color: #1d1f29;
        padding: 10px;
        box-decoration-break: clone;
    }

    .tool-bar {
        flex: 1;
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
    }

    .tool-bar > div {
        color: darkgrey;
        cursor: pointer;
        padding: 10px;
        font-size: 16px;
        margin-right: 10px;
    }

</style>