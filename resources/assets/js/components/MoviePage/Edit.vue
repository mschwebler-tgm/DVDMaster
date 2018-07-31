<template>
    <div class="z-depth-3">
        <div class="row">
            <div class="input-field col s6">
                <input id="custom_title" type="text" class="validate" v-model="movie.title">
                <label for="custom_title">Title</label>
            </div>
            <div class="input-field col s6">
                <star-rating :increment="0.5" style="z-index: 1000;" inactive-color="#e6e6e6" class="right"
                             active-color="#FFD700" v-model="movie.custom_rating" :show-rating="false"
                             :border-width="0" :star-size="40"></star-rating>
            </div>
            <div class="input-field col s12">
                <textarea id="custom_overview" class="validate materialize-textarea" v-model="movie.overview"></textarea>
                <label for="custom_overview">Overview</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <actors-input @change="updateActors" :initial="movie.actors"></actors-input>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <genres-input @change="updateGenres" :initial="movie.genres"></genres-input>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s3">
                <input type="text" class="datepicker">
            </div>
        </div>
        <div class="row">
            <div class="col s6">
                <a class="waves-effect waves-light btn" @click="openCustomBackdropDialog()"><i class="material-icons left">collections</i>Background</a>
                <a class="waves-effect waves-light btn" @click="clearBackdrop()" v-if="custom_backdrop_preview"><i class="material-icons left">clear</i>Clear</a>
                <input type="file" id="custom_backdrop" style="display: none" accept="image/*" @change="previewBackdrop">
                <img :src="custom_backdrop_preview" id="custom_backdrop_preview" />
            </div>
        </div>
        <div class="row">
            <div class="col s6">
                <a class="waves-effect waves-light btn" @click="openCustomCoverDialog()"><i class="material-icons left">collections_bookmark</i>Cover</a>
                <a class="waves-effect waves-light btn" @click="clearCover()" v-if="custom_poster_preview"><i class="material-icons left">clear</i>Clear</a>
                <input type="file" id="custom_cover" style="display: none" accept="image/*" @change="previewCover">
                <img :src="custom_poster_preview" id="custom_cover_preview" />
            </div>
        </div>
    </div>
</template>

<script>
    import StarRating from 'vue-star-rating';

    export default {
        props: ['movie'],
        data() {
            return {
                custom_backdrop_preview: null,
                custom_poster_preview: null
            }
        },
        mounted() {
            M.updateTextFields();
            M.textareaAutoResize($('#custom_overview'));
        },
        methods: {
            openCustomCoverDialog() {
                $('#custom_cover').trigger('click');
            },
            openCustomBackdropDialog() {
                $('#custom_backdrop').trigger('click');
            },
            setPreviewImage(input, element) {
                if (input.files && input.files[0]) {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        this[element] = e.target.result;
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            },
            previewCover() {
                this.setPreviewImage($('#custom_cover')[0], 'custom_poster_preview');
            },
            previewBackdrop() {
                this.setPreviewImage($('#custom_backdrop')[0], 'custom_backdrop_preview');
            },
            clearCover() {
                $('#custom_cover').val(null);
                this.custom_poster_preview = null;
            },
            clearBackdrop() {
                $('#custom_backdrop').val(null);
                this.custom_backdrop_preview = null;
            },
            updateActors(actors) {
                this.movie.actors = actors;
            },
            updateGenres(genres) {
                this.movie.genres = genres;
            }
        },
        components: {
            StarRating
        }
    }
</script>

<style scoped>

</style>