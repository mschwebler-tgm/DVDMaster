<template>
    <div class="z-depth-3 movie-edit">
        <div class="pictures">
            <div class="movie-cover" :style="{'background-image': coverSrc}"
                 @click="openCustomCoverDialog()">
                <div class="darken">
                    <h5 class="grey-text">Cover</h5>
                </div>
                <input type="file" id="custom_cover" style="display: none" accept="image/*" @change="previewCover">
            </div>
            <div class="movie-backdrop" :style="{'background-image': backdropSrc}"
                 @click="openCustomBackdropDialog()">
                <div class="darken">
                    <h5 class="grey-text">Background</h5>
                </div>
                <input type="file" id="custom_backdrop" style="display: none" accept="image/*" @change="previewBackdrop">
            </div>
        </div>
        <div class="row" style="padding-top: 20px; margin-bottom: 0;">
            <div class="input-field col s6">
                <input id="custom_title" type="text" class="validate" v-model="movie.title">
                <label for="custom_title">Title</label>
            </div>
            <div class="input-field col s6">
                <star-rating :increment="0.5" style="z-index: 1000;" inactive-color="#e6e6e6" class="right"
                             active-color="#FFD700" v-model="movie.custom_rating" :show-rating="false"
                             :border-width="0" :star-size="40"></star-rating>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s6">
                <input id="comment" type="text" class="validate" v-model="movie.comment">
                <label for="comment">Comment</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <textarea id="custom_overview" class="validate materialize-textarea" v-model="movie.overview"></textarea>
                <label for="custom_overview">Overview</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <label class="active">Actors</label>
                <actors-input @change="updateActors" :initial="movie.actors"></actors-input>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <label class="active">Genres</label>
                <genres-input @change="updateGenres" :initial="movie.genres"></genres-input>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s3">
                <label class="active">Release date</label>
                <input type="text" class="datepicker" v-model="movie.release_date">
            </div>
        </div>
        <div class="row">
            <div style="padding-left: 15px;">
                <label>
                    <input type="checkbox" v-model="movie.blue_ray" />
                    <span>Blue-Ray</span>
                </label>
                <br><br>
                <label>
                    <input type="checkbox" v-model="movie.based_on_book" />
                    <span>Based on book</span>
                </label>
                <br><br>
                <label>
                    <input type="checkbox" v-model="movie.true_story" />
                    <span>True Story</span>
                </label>
            </div>
        </div>
        <div class="save">
            <a class="waves-effect waves-light btn right" @click="save()">Save</a>
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
            M.Datepicker.init(document.querySelectorAll('.datepicker'), {
                autoclose: true,
                format: 'dd. mm. yyyy',
                yearRange: [1950, (new Date()).getFullYear()],
                firstDay: 1,
                onSelect: dateEvent => {
                    let date = new Date(dateEvent);
                    let year = date.getFullYear();
                    let month = date.getMonth() < 10 ? '0' + date.getMonth() : date.getMonth();
                    let day = date.getDay() < 10 ? '0' + date.getDay() : date.getDay();
                    this.customMovie.release_date = year + '-' + month + '-' + day;
                },
            });
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
            },
            save() {
                let payload = new FormData();
                payload.set('movie', JSON.stringify(this.movie));
                let coverInput = $('#custom_cover')[0];
                if (coverInput.files && coverInput.files[0]) {
                    payload.append('custom_poster', coverInput.files[0], coverInput.files[0].name);
                }
                let backdropInput = $('#custom_backdrop')[0];
                if (backdropInput.files && backdropInput.files[0]) {
                    payload.append('custom_backdrop', backdropInput.files[0], backdropInput.files[0].name);
                }
                axios.post('/api/movie/' + this.movie.id + '/update', payload).then(res => {

                });
            }
        },
        computed: {
            backdropSrc() {
                return 'url(' + (this.custom_backdrop_preview || this.$root.getImagePath(this.movie.backdrop_path, 'original')) + ')';
            },
            coverSrc() {
                return 'url(' + (this.custom_poster_preview || this.$root.getImagePath(this.movie.poster_path, 'original')) + ')';
            }
        },
        components: {
            StarRating
        }
    }
</script>

<style scoped>

    .movie-edit > .row{
        padding: 0 15px;
    }

    .pictures {
        width: 100%;
        display: flex;
    }

    .movie-cover {
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center center;
        position: relative;
        height: 350px;
        width: 230px;
        border: 3px groove #adbfbf;
        cursor: pointer;
    }

    .movie-backdrop {
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center center;
        position: relative;
        height: 350px;
        flex: 1;
        border: 3px groove #adbfbf;
        cursor: pointer;
    }

    .darken {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.22);
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .movie-backdrop:hover .darken, .movie-cover:hover .darken {
        display: none;
    }

    .save {
        display: flex;
        justify-content: flex-end;
        padding: 0 15px 15px 15px;
    }
</style>