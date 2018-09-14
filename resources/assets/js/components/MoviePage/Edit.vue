<template>
    <div class="md-elevation-3 movie-edit">
        <div class="pictures"> <!-- images -->
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
        <div class="pad" style="position: relative;">
            <md-field id="title">
                <label>Title</label>
                <md-input v-model="movie.title" placeholder="Title"></md-input>
            </md-field>
            <div style="position: absolute; top: 15px; right: 15px;">
                <movie-rating :movie="movie" @newCustomRating="updateRating(movie, $event)"></movie-rating>
            </div>
            <md-field>
                <label>Overview</label>
                <md-textarea v-model="movie.overview" md-autogrow></md-textarea>
            </md-field>
            <md-field id="comment">
                <label>Comment</label>
                <md-input v-model="movie.comment" placeholder="Comment"></md-input>
            </md-field>
            <div style="margin-bottom: 24px;">
                <tags-input v-model="movie.actors" type="actors" image-key="profile_path"></tags-input>
                <md-chip v-for="(actor, index) in movie.actors" :key="actor.id" class="custom-chip" md-deletable @md-delete="movie.actors.splice(index, 1)">{{ actor.name }}</md-chip>
            </div>
            <div style="margin-bottom: 24px;">
                <tags-input v-model="movie.genres" type="genres" image-key="profile_path"></tags-input>
                <md-chip v-for="(genre, index) in movie.genres" :key="genre.id" class="custom-chip" md-deletable @md-delete="movie.genres.splice(index, 1)">{{ genre.name }}</md-chip>
            </div>
            <md-datepicker v-model="movie.release_date" md-immediately id="release-date">
                <label>Release date</label>
            </md-datepicker>
            <md-checkbox v-model="movie.blue_ray"><md-icon>album</md-icon>&nbsp;&nbsp;Blue-Ray</md-checkbox><br>
            <md-checkbox v-model="movie.based_on_book"><md-icon>event</md-icon>&nbsp;&nbsp;Based on book</md-checkbox><br>
            <md-checkbox v-model="movie.true_story"><md-icon>book</md-icon>&nbsp;&nbsp;True Story</md-checkbox><br>
            <div class="flex flex-justify-end">
                <md-button class="md-raised md-accent" @click="save">Save</md-button>
            </div>
        </div>
    </div>
</template>

<script>
    import StarRating from 'vue-star-rating';
    import moment from 'moment';

    export default {
        props: ['movie'],
        data() {
            return {
                custom_backdrop_preview: null,
                custom_poster_preview: null
            }
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
            save() {
                let payload = new FormData();
                if (this.movie.release_date) {
                    this.movie.release_date = moment(this.movie.release_date).format('YYYY-MM-DD');
                }

                payload.set('movie', JSON.stringify(this.movie));
                let coverInput = $('#custom_cover')[0];
                if (coverInput.files && coverInput.files[0]) {
                    payload.append('custom_poster', coverInput.files[0], coverInput.files[0].name);
                }
                let backdropInput = $('#custom_backdrop')[0];
                if (backdropInput.files && backdropInput.files[0]) {
                    payload.append('custom_backdrop', backdropInput.files[0], backdropInput.files[0].name);
                }
                this.$store.dispatch('MOVIES_ACTION_UPDATE', {payload, id: this.movie.id});
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

    .custom-chip {
        margin-left: 4px;
        margin-bottom: 4px;
    }

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

    #title, #comment, #release-date {
        width: 50%;
    }

    @media only screen and (max-width: 600px){
        #title {
            margin-top: 55px;
        }

        #title, #comment, #release-date {
            width: 100%;
        }
    }
</style>