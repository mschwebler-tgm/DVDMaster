<template>
    <div class="md-elevation-3" style="position: relative">
        <div class="pictures"> <!-- images -->
            <div class="movie-cover" :style="{'background-image': coverSrc}" id="custom_poster_preview"
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
        <div class="pad">
            <md-field>
                <label>Title</label>
                <md-input v-model="customMovie.title" placeholder="Title"></md-input>
            </md-field>
            <md-field>
                <label>Duration</label>
                <md-input v-model="customMovie.duration" type="number"></md-input>
                <span class="md-suffix">min</span>
            </md-field>
            <star-rating :increment="0.5" style="z-index: 1000; position: absolute; top: 15px; right: 15px;" inactive-color="#e6e6e6" class="right"
                         active-color="var(--md-theme-default-accent, #ff5252)" v-model="customMovie.custom_rating" :show-rating="false"
                         :border-width="0" :star-size="40"></star-rating>
            <md-field>
                <label>Overview</label>
                <md-textarea v-model="customMovie.overview" md-autogrow></md-textarea>
            </md-field>
            <div style="margin-bottom: 24px;">
                <label>Genres</label>
                <genres-input @change="genres => customMovie.genres = genres"></genres-input>
            </div>
            <div style="margin-bottom: 24px;">
                <label>Actors</label>
                <actors-input @change="actors => customMovie.actors = actors"></actors-input>
            </div>
            <md-datepicker v-model="customMovie.release_date" md-immediately id="release-date">
                <label>Release date</label>
            </md-datepicker>
            <md-checkbox v-model="customMovie.blue_ray"><md-icon>album</md-icon>&nbsp;&nbsp;Blue-Ray</md-checkbox><br>
            <md-checkbox v-model="customMovie.based_on_book"><md-icon>event</md-icon>&nbsp;&nbsp;Based on book</md-checkbox><br>
            <md-checkbox v-model="customMovie.true_story"><md-icon>book</md-icon>&nbsp;&nbsp;True Story</md-checkbox><br>
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
        data() {
            return {
                customMovie: {},
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
                this.customMovie.release_date = moment(this.customMovie.release_date).format('YYYY-MM-DD');
                let payload = new FormData();
                payload.set('movie', JSON.stringify(this.customMovie));
                payload.set('is_custom', 'true');
                let coverInput = $('#custom_cover')[0];
                if (coverInput.files && coverInput.files[0]) {
                    payload.append('custom_poster', coverInput.files[0], coverInput.files[0].name);
                }
                let backdropInput = $('#custom_backdrop')[0];
                if (backdropInput.files && backdropInput.files[0]) {
                    payload.append('custom_backdrop', backdropInput.files[0], backdropInput.files[0].name);
                }
                this.$store.dispatch('MOVIES_ACTION_SAVE', payload);
            }
        },
        computed: {
            backdropSrc() {
                return 'url(' + this.custom_backdrop_preview + ')';
            },
            coverSrc() {
                return 'url(' + this.custom_poster_preview + ')';
            }
        },
        components: {
            StarRating
        }
    }
</script>

<style scoped>
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

</style>