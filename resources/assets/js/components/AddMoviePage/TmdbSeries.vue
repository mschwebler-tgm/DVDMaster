<template>
    <div style="overflow: hidden">
        <div class="md-elevation-3">
            <div class="movie-backdrop" :style="{'background-image': series.backdrop_path ? 'url(' + $root.getImagePath(series.backdrop_path, 'original') + ')' : ''}">
                <div>
                    <div class="clear-icon" @click="clearSeries">
                        <md-icon class="pointer white-text">clear</md-icon>
                        <md-tooltip md-direction="right">Clear</md-tooltip>
                    </div>
                    <div class="pad input-fields">
                        <div class="flex">
                            <div style="width: 270px">
                                <md-field class="md-custom-input">
                                    <label class="white-text">Name</label>
                                    <md-input v-model="series.name" placeholder="Name" id="tmdb_series_name" class="white-text"
                                              @blur="searchSeries" @keydown.enter="searchSeries" @keydown.tab="searchSeries" @keydown="registerChange"></md-input>
                                </md-field>
                                <div class="flex flex-align-center">
                                    <movie-rating :movie="series" :showCustomRating="!!series.custom_rating" style="width: 200px; height: 46px"></movie-rating>
                                    <fade-transition :duration="200">
                                        <div v-show="series.vote_count && !series.custom_rating">
                                            <md-icon style="color: darkgrey; margin-left: 10px">info</md-icon>
                                            <md-tooltip md-direction="right">{{ series.vote_count }} users rated on themoviedb.org</md-tooltip>
                                        </div>
                                    </fade-transition>
                                </div>
                            </div>
                            <div style="position: relative; flex: 1">
                                <md-content class="poster-container md-xsmall-hide md-xlarge-hide flex md-accent md-elevation-3" :class="{'hide': hideDetails}">
                                    <div>
                                        <img :src="series.poster_path ? ($root.getImagePath(series.poster_path, 'w185'))
                                                           : ('https://dentallabor-gruettner.de/wp-content/uploads/2017/05/placeholder.gif')"
                                             class="movie-poster background-center">
                                    </div>
                                    <div style="flex: 1" class="pad">
                                        <template v-if="series.episode_run_time && series.episode_run_time.length > 0"><p><md-icon>timer</md-icon>&nbsp;&nbsp;{{ series.episode_run_time[0] }} min / episode</p></template>
                                        <template v-if="series.homepage"><p><a :href="series.homepage" target="_blank"><md-icon style="text-decoration: none">web</md-icon>&nbsp;&nbsp;<span style="color: var(--md-theme-default-text-primary-on-accent, #fff); text-decoration: underline;">Homepage</span></a></p></template>
                                        <md-chip v-for="genre in series.genres" :key="genre.id" style="margin-bottom: 5px">{{ genre.name }}</md-chip>
                                        <template v-if="series.status"><p>Status: {{ series.status }}</p></template>
                                    </div>
                                    <div class="flex flex-justify-center" style="position: absolute; left: 0; bottom: -24px; width: 100%; height: 24px; z-index: 100">
                                        <md-content class="md-accent">
                                            <div @click="toggleDetails">
                                                <md-icon class="pointer">{{ hideDetails ? 'keyboard_arrow_down' : 'keyboard_arrow_up' }}</md-icon>
                                            </div>
                                        </md-content>
                                    </div>
                                </md-content>
                            </div>
                        </div>
                        <md-field v-if="showTextarea" style="width: calc(100% - 15px);" class="overview md-custom-input">
                            <label class="white-text">Overview</label>
                            <md-textarea v-model="series.overview" class="white-text" md-autogrow ref="overview"></md-textarea>
                        </md-field>
                    </div>
                    <div class="xlarge-poster-container pad flex flex-align-center">
                        <div class="flex">
                            <div>
                                <img :src="series.poster_path ? ($root.getImagePath(series.poster_path, 'w185'))
                                                           : ('https://dentallabor-gruettner.de/wp-content/uploads/2017/05/placeholder.gif')"
                                     class="movie-poster background-center">
                            </div>
                            <md-content class="md-accent pad white-text" style="background-color: transparent">
                                <template v-if="series.episode_run_time && series.episode_run_time.length > 0"><p><md-icon>timer</md-icon>&nbsp;&nbsp;{{ series.episode_run_time[0] }} min / episode</p></template>
                                <template v-if="series.homepage"><p><a :href="series.homepage" target="_blank"><md-icon class="white-text" style="text-decoration: none">web</md-icon>&nbsp;&nbsp;<span style="color: var(--md-theme-default-text-primary-on-accent, #fff); text-decoration: underline;">Homepage</span></a></p></template>
                                <md-chip v-for="genre in series.genres" :key="genre.id" class="md-accent" style="margin-bottom: 5px;">{{ genre.name }}</md-chip>
                                <template v-if="series.status"><p>Status: {{ series.status }}</p></template>
                            </md-content>
                        </div>
                    </div>
                </div>
            </div>
            <md-content class="pad md-accent">
                <span class="md-caption">Custom</span>
                <div class="pad">
                    <div class="white-text flex">
                        <md-checkbox v-model="series.blue_ray"><md-icon>album</md-icon>&nbsp;&nbsp;Blue-Ray</md-checkbox><br>
                        <md-checkbox v-model="series.based_on_book"><md-icon>event</md-icon>&nbsp;&nbsp;Based on book</md-checkbox><br>
                        <md-checkbox v-model="series.true_story"><md-icon>book</md-icon>&nbsp;&nbsp;True Story</md-checkbox><br>
                    </div>
                    <md-field class="md-custom-input">
                        <label>Comment</label>
                        <md-input v-model="series.comment" placeholder="Comment"></md-input>
                    </md-field>
                </div>
            </md-content>
            <div class="seasons" id="seasons" style="background-color: black;" v-show="readyForSeasons">
                <template v-if="readyForSeasons && !forceHideSeasons">
                    <swiper :options="swiperOption" v-if="readyForSeasons">
                        <swiper-slide v-for="(season, index) in series.seasons" :key="season.id" ref="swiper"
                                      v-if="season.poster_path">
                            <div class="season-sugg">
                                <div class="season-sugg-cover background-image-center"
                                     :style="'background-image: url(' + $root.getImagePath(season.poster_path, 'w185') + ')'">
                                    <!--<img :src="$root.getImagePath(season.poster_path, 'w185')" class="background-center"/>-->
                                    <div class="episode-params">
                                        <div style="padding: 5px 15px;">
                                            <span class="md-title">{{ season.name }}</span>
                                            <br>
                                            <span class="md-caption">{{ season.episode_count }} Episodes</span>
                                        </div>
                                    </div>
                                    <div class="season-remove">
                                        <div class="season-remove-icon" @click="removeSeason(index, season)">
                                            <md-icon class="md-accent md-size-2x">remove_circle_outline</md-icon>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </swiper-slide>
                    </swiper>
                </template>
            </div>
        </div>
        <div class="flex flex-justify-end">
            <md-button class="md-raised md-accent" @click="save">Save</md-button>
        </div>
    </div>
</template>

<script>
    import '../../../../../node_modules/swiper/dist/css/swiper.css';
    import {swiper, swiperSlide} from 'vue-awesome-swiper';
    import StarRating from 'vue-star-rating';

    export default {
        data() {
            return {
                series: {},
                removedSeasons: [],
                swiperOption: {
                    slidesPerView: window.innerWidth / 230,
                    freeMode: true,
                    spaceBetween: 0,
                },
                lastSearchTerm: '',
                searchTermChanged: false,
                showTextarea: true,
                hideDetails: true,
                forceHideSeasons: false
            }
        },
        mounted() {
            let $seasons = $('#seasons');
            let interval = setInterval(() => {
                this.updateSlidesPerView($seasons);
                if (this.swiperOption.slidesPerView > 0) {
                    clearInterval(interval);
                }
            }, 100);

            let update;
            window.onresize = () => {
                clearTimeout(update);
                update = setTimeout(() => this.updateSlidesPerView($seasons), 100);
            }
        },
        methods: {
            updateSlidesPerView($seasons) {
                this.forceHideSeasons = true;
                this.swiperOption.slidesPerView = $seasons.width() / 176;
                setTimeout(() => {
                    this.forceHideSeasons = false;
                    this.$nextTick(() => this.$forceUpdate());
                }, 100);
            },
            searchSeries() {
                let title = $('#tmdb_series_name').val();
                if (title === this.lastSearchTerm || !this.searchTermChanged) { return }
                this.$root.theMovieDb.search.getTv({query: title}, res => {
                    res = JSON.parse(res);
                    if (res.results && res.results.length > 0) {
                        this.getSeries(res.results.shift().id);
                        this.removedSeasons = [];
                    } else {
                        this.$root.toast('No search results');
                    }
                }, err => {
                    this.$root.toast(err);
                });
            },
            getSeries(id) {
                this.$root.theMovieDb.common.client({
                        url: "tv/" + id + this.$root.theMovieDb.common.generateQuery()
                    },
                    res => {
                        let seriesRes = JSON.parse(res);
                        if (seriesRes) {
                            this.showTextarea = false;
                            this.series = seriesRes;
                            this.$nextTick(() => {
                                this.showTextarea = true;
                            });
                            this.searchTermChanged = false;
                            this.hideDetails = false;
                        }
                    },
                    err => {
                        this.$root.toast(err);
                    }
                );
            },
            clearSeries() {
                this.series = {};
            },
            registerChange(event) {
                if (!this.searchTermChanged) {
                    this.searchTermChanged = ['Enter', 'Tab', 'Control', 'Alt', 'Shift', 'Capslock', 'Escape',
                        'F1', 'F2', 'F3', 'F4', 'F5', 'F6', 'F7', 'F8', 'F9', 'F10', 'F11', 'F12',
                        'AudioVolumeMute', 'AudioVolumeDown', 'AudioVolumeUp', 'End', 'PageDown', 'PageUp',
                        'Home', 'Insert', 'NumLock'].indexOf(event.key) === -1;
                }
            },
            save() {
                let payload = new FormData();
                payload.set('series', JSON.stringify(this.series));
                payload.set('removed_seasons', JSON.stringify(this.removedSeasons));
                payload.set('is_custom', 'false');
                this.$store.dispatch('SERIES_ACTION_SAVE', payload).then(() => {
                    this.series.comment = null;
                    this.series.blue_ray = null;
                    this.series.true_story = null;
                    this.series.based_on_book = null;
                });
            },
            toggleDetails() {
                this.hideDetails = !this.hideDetails;
            },
            removeSeason(index, season) {
                this.series.seasons.splice(index, 1);
                this.removedSeasons.push(season.id);
            }
        },
        computed: {
            readyForSeasons() {
                return this.series.seasons && this.series.seasons.length > 0 && !this.forceHideSeasons;
            }
        },
        components: {
            swiper,
            swiperSlide,
            StarRating
        }
    }
</script>

<style scoped>

    .clear-icon {
        position: absolute;
        top: 15px;
        right: 15px;
    }

    .white-text, .white-text::placeholder {
        color: white !important;
        -webkit-text-fill-color: white !important;
    }

    .input-fields {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    .movie-poster {
        width: 185px;
        height: 278px;
        border: 3px groove #adbfbf;
    }

    #tmdb_series_name {
        font-size: 26px;
    }

    .seasons {
        height: 250px;
        width: 100%;
    }

    .swiper-container {
        height: 100%;
    }

    .swiper-slide {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .season-sugg {
        width: 100%;
        height: 100%;
        position: relative;
    }

    .season-sugg-cover {
        margin: 15px;
        height: 220px;
    }

    .season-sugg-cover:hover {
        outline: 2px solid white;
        transition: all .15s ease-in-out;
        cursor: pointer;
    }

    .season-sugg-cover:hover .episode-params {
        /*opacity: 0;*/
        background-color: transparent;
    }

    .season-sugg-cover:hover .episode-params span {
        color: rgba(255, 255, 255, 0.45) !important;
    }

    .season-sugg-cover:hover .season-remove {
        display: flex;
        background-color: rgba(0, 0, 0, 0.56);
    }

    .season-sugg-active {
        outline: 2px solid white;
        border: 2px solid white;
    }

    .episode-params {
        opacity: 1;
        position: absolute;
        left: 15px;
        bottom: 15px;
        background-color: rgba(0, 0, 0, 0.56);
        line-height: 26px;
        width: calc(100% - 30px);
        z-index: 2;
    }

    .episode-params span {
        color: white;
        -webkit-transition: color .3s;
        -moz-transition: color .3s;
        -ms-transition: color .3s;
        -o-transition: color .3s;
        transition: color .3s;
    }

    .season-remove {
        background-color: transparent;
        position: absolute;
        top: 15px;
        left: 15px;
        width: calc(100% - 30px);
        height: calc(100% - 30px);
        display: none;
        justify-content: center;
        align-items: center;
        -webkit-transition: background-color .3s;
        -moz-transition: background-color .3s;
        -ms-transition: background-color .3s;
        -o-transition: background-color .3s;
        transition: background-color .3s;
    }

    .season-remove-icon:hover {
        animation: pulse 2s infinite;
        -webkit-animation: pulse 2s infinite;
    }

    @-webkit-keyframes pulse {
        from {
            -webkit-transform: scale3d(1, 1, 1);
            transform: scale3d(1, 1, 1);
        }

        50% {
            -webkit-transform: scale3d(1.25, 1.25, 1.25);
            transform: scale3d(1.25, 1.25, 1.25);
        }

        to {
            -webkit-transform: scale3d(1, 1, 1);
            transform: scale3d(1, 1, 1);
        }
    }

    @keyframes pulse {
        from {
            -webkit-transform: scale3d(1, 1, 1);
            transform: scale3d(1, 1, 1);
        }

        50% {
            -webkit-transform: scale3d(1.25, 1.25, 1.25);
            transform: scale3d(1.25, 1.25, 1.25);
        }

        to {
            -webkit-transform: scale3d(1, 1, 1);
            transform: scale3d(1, 1, 1);
        }
    }

    .hide-indicator {
        position: absolute;
        bottom: 0;
        left: calc(50% - 20px);
        width: 40px;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        background-color: rgba(0, 0, 0, 0.67);
        z-index: 100;
        opacity: 0;
        cursor: pointer;
        -webkit-transition: opacity .5s ease-in-out;
        -moz-transition: opacity .5s ease-in-out;
        -ms-transition: opacity .5s ease-in-out;
        -o-transition: opacity .5s ease-in-out;
        transition: opacity .5s ease-in-out;
    }

    #seasons:hover .hide-indicator {
        opacity: 1;
    }

    #seasonsTrigger {
        height: 0;
        padding: 0;
        border: none;
    }

    #seasons {
        border: none;
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        box-shadow: none;
        margin: 0;
    }

    #seasons .collapsible-body {
        border: none;
        padding: 0;
    }

    #seasons .collapsible-body > .row {
        margin-bottom: 0;
    }

    .movie-backdrop {
        background-repeat: no-repeat;
        background-size: cover;
        position: relative;
    }

    .movie-backdrop > div {
        height: 420px;
        padding: 15px;
        margin-bottom: 0;
        background: linear-gradient(
                rgba(0, 0, 0, 0.65),
                rgba(0, 0, 0, 0.65)
        );
    }

    .poster-container {
        height: calc(278px + 45px);
        position: absolute;
        top: 0;
        left: 15px;
        background-color: rgba(128, 128, 128, 1);
        padding: 30px 15px 15px;
        margin-right: 15px;
        margin-top: -30px;
        z-index: 10;
        width: calc(100% - 30px);
        transition: .3s ease-in-out;
    }

    .poster-container.hide {
        top: calc(-278px - 45px);
    }

    .overview {
        width: calc(100% - 15px);
    }

    .xlarge-poster-container {
        display: none;
    }

    @media only screen and (min-width: 1904px){
        .input-fields {
            float: left;
            width: 57%;
        }
        .xlarge-poster-container {
            display: block;
        }
    }

</style>