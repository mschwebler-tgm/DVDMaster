<template>
    <div>
        <md-datepicker md-immediately v-model="customDate" id="movie-date-picker" style="height: 0; visibility: hidden; position: fixed"/>
        <user-modal @userSelected="selectUser" :show.sync="showUserModal"></user-modal>
        <retrieval-modal @retrieved="retrieveMovie" :show.sync="showRetrieveModal"></retrieval-modal>
        <div class="md-elevation-3" v-show="movie">
            <div class="col s12 no-padding" v-if="movie">
                <div class="backdrop-image"
                     :style="{ 'backgroundImage': 'url(' + $root.getImagePath(movie.backdrop_path, 'w1280') + ')'}">
                    <i class="material-icons pointer" id="back-button" @click="$router.go(-1)">arrow_back</i>
                    <div class="poster-image"
                         :style="{ 'backgroundImage': 'url(' + $root.getImagePath(movie.poster_path, 'w185') + ')' }"></div>
                    <div class="movie-title">
                        <span>{{ movie.title }}</span>
                    </div>
                </div>
            </div>
            <div class="col s12"> <!-- toolbar -->
                <div class="movie-header">
                    <div class="poster-spacer"></div>
                    <div class="tool-bar" v-if="$root.isAdmin">
                        <div @click="$root.$router.push('/movie/' + movie.id + '/edit')" class="flex flex-justify-center flex-align-center"><md-icon>edit</md-icon>&nbsp;&nbsp;Edit</div>
                        <template v-if="movie">
                            <template v-if="movie.pending_rental.length === 0">
                                <div @click="showUserModal = true" class="flex flex-justify-center flex-align-center">
                                    <md-icon>assignment_ind</md-icon>&nbsp;&nbsp;Borrow
                                </div>
                            </template>
                            <template v-else>
                                <div @click="showRetrieveModal = true" class="flex flex-justify-center flex-align-center">
                                    <md-icon>assignment_ind</md-icon>&nbsp;&nbsp;Borrowed by {{ movie.pending_rental[0].user.name }}
                                </div>
                            </template>
                        </template>
                        <md-menu md-direction="bottom-start" id="just-seen-menu">
                            <div md-menu-trigger style="position: relative; top: 3px;">Just seen <template v-if="movie && movie.last_seen">(last: {{ lastSeen }})</template></div>
                            <md-menu-content>
                                <md-menu-item class="hover-effect" @click="updateLastSeenCustom(0, 'days')"><span><md-icon>access_time</md-icon> Now</span></md-menu-item>
                                <md-menu-item class="hover-effect" @click="pickLastSeen"><span><md-icon>calendar_today</md-icon> Pick Date</span></md-menu-item>
                                <md-divider></md-divider>
                                <md-menu-item class="hover-effect" @click="updateLastSeenCustom(6, 'days')"><span>1 week ago</span></md-menu-item>
                                <md-menu-item class="hover-effect" @click="updateLastSeenCustom(1, 'months')"><span>1 month ago</span></md-menu-item>
                                <md-menu-item class="hover-effect" @click="updateLastSeenCustom(1, 'years')"><span>1 year ago</span></md-menu-item>
                            </md-menu-content>
                        </md-menu>
                        <div style="margin-left: auto; margin-right: 0;" @click="deleteMovie" class="flex flex-justify-center flex-align-center">
                            <md-icon>delete</md-icon>
                            <md-tooltip md-direction="right">Delete</md-tooltip>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12">
                <div class="movie-body">
                    <div class="poster-spacer movie-params-table">
                        <div style="width: 185px;" :style="$root.isAdmin ? '' : 'padding-top: 55px'"> <!-- width of poster -->
                            <md-table>
                                <md-table-row v-for="(row, index) in tableContents" :key="index">
                                    <md-table-cell v-html="row" style="float: right;"></md-table-cell>
                                </md-table-row>
                            </md-table>
                        </div>
                    </div>
                    <div class="overview">
                        <template v-if="movie && movie.overview">
                            <span class="md-body-1">{{ movie.overview }}</span>
                            <br><br>
                            <div class="flex flex-justify-end ">
                                <span class="md-caption">{{ movie.comment ? '*' + movie.comment : '' }}</span>
                            </div>
                        </template>
                        <div class="genres" v-if="movie && movie.genres">
                            <md-chip v-for="genre in movie.genres" :key="genre.id">
                                {{ genre.name }}
                            </md-chip>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12 no-padding" id="actors">   <!-- actors -->
                <template v-if="readyToShowActors && showActors && movie && movie.actors && !showUserModal && !showRetrieveModal">
                    <swiper :options="swiperOption">
                        <swiper-slide v-for="actor in movie.actors" :key="actor.id">
                            <div class="actor" :style="{ 'backgroundImage': 'url(' + $root.getImagePath(actor.profile_path, 'w185') + ')'}">
                                <div class="actor-name">
                                    {{ actor.name }}
                                </div>
                            </div>
                        </swiper-slide>
                        <swiper-slide>
                            <div class="add-actor" style="display: flex; justify-content: center; align-items: center;">
                                Add actor
                            </div>
                        </swiper-slide>
                    </swiper>
                </template>
                <template v-else> <!-- placeholder -->
                    <div style="height: 278px;"></div>
                </template>
            </div>
        </div>
        <input type="text" class="datepicker" id="last-seen-date-picker" style="display: none;" @change="updateLastSeen($event.target.value)">
    </div>
</template>

<script>
    import moment from 'moment';
    import '../../../../../node_modules/swiper/dist/css/swiper.css';
    import {swiper, swiperSlide} from 'vue-awesome-swiper';
    import StarRating from 'vue-star-rating';

    export default {
        props: ['id', 'movie'],
        data() {
            return {
                swiperOption: {
                    slidesPerView: window.innerWidth / 230,
                    freeMode: true,
                    spaceBetween: 50,
                },
                readyToShowActors: false,
                retrieveModal: null,
                retrieveDatepicker: null,
                retrieveDate: null,
                retrieveSlider: null,
                customDate: null,
                showUserModal: false,
                showRetrieveModal: false
            }
        },
        created() {
            this.$store.dispatch('USERS_ACTION_GET_All_EXCEPT_ME');
        },
        mounted() {
            this.swiperOption.slidesPerView = $('#actors').width() / 185;
            this.readyToShowActors = true;
        },
        methods: {
            deleteMovie() {
                this.$store.dispatch('MOVIES_ACTION_DELETE', this.movie.id);
            },
            updateLastSeen(date) {
                this.$store.dispatch('MOVIES_ACTION_UPDATE_LAST_SEEN', {id: this.movie.id, date}).then(res => {
                    this.movie.last_seen = res.data;
                });
            },
            updateLastSeenCustom(amount, type) {
                this.updateLastSeen(moment().subtract(amount, type).format('YYYY-MM-DD hh:mm:ss'));
            },
            pickLastSeen() {
                let position = $('#just-seen-menu').position();
                let $datePicker = $('#movie-date-picker > i');
                $('#movie-date-picker').css({
                    position: 'fixed',
                    top: position.top,
                    left: position.left
                });
                $datePicker.trigger('click');
            },
            selectUser(user) {
                this.showUserModal = false;
                this.$store.dispatch('MOVIES_ACTION_BORROW', {id: this.movie.id, user}).then(res => {
                    Vue.set(this.movie, 'pending_rental', [res.data]);
                    // Vue.set(this.movie.pending_rental, 'rented_by', [user]);
                });
            },
            retrieveMovie(params) {
                switch (params.quality) {
                    case 'DVD lost': params.quality = 'lost';
                        break;
                    case 'Bad Shape': params.quality = 'bad';
                        break;
                    case 'Some damage': params.quality = 'meh';
                        break;
                    case 'Minor scratches': params.quality = 'okay';
                        break;
                    case 'Original': params.quality = 'good';
                        break;
                }
                params.date = moment(params.data).format('YYYY-MM-DD');
                this.$store.dispatch('MOVIES_ACTION_RETRIEVE', {id: this.movie.id, payload: params}).then(() => {
                    Vue.set(this.movie, 'pending_rental', []);
                });
            },
        },
        computed: {
            lastSeen() {
                if (!this.movie.last_seen) return null;
                return moment(this.movie.last_seen).fromNow();
            },
            tableContents() {
                if (!this.movie) {
                    return [];
                }

                let table = [
                    this.movie.duration + ' min. <i class="material-icons table-icons">timer</i>',
                    this.movie.release_date + ' <i class="material-icons table-icons">calendar_today</i>',
                ];
                if (this.movie.based_on_book) {
                    table.push('Based on book <i class="material-icons table-icons"> check </i>')
                }
                if (this.movie.true_story) {
                    table.push('True story <i class="material-icons table-icons"> check </i>')
                }
                if (this.movie.blue_ray) {
                    table.push('Blue-ray <i class="material-icons table-icons"> check </i>')
                }

                return table;
            },
            showActors() {
                return !this.showUserModal && !this.showRetrieveModal;
            }
        },
        watch: {
            customDate(newVal, oldVal) {
                if (oldVal !== newVal && (newVal.getHours() + newVal.getMinutes() + newVal.getSeconds()) === 0 && (!oldVal || newVal.valueOf() !== oldVal.valueOf())) {
                    this.updateLastSeen(moment(newVal).format('YYYY-MM-DD hh:mm:ss'));
                }
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
        line-height: 55px;
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
        padding-top: 5px;
        padding-bottom: 5px;
        border-bottom: 1px solid #dadada;
        max-height: 55px;
    }

    .tool-bar > div {
        color: darkgrey;
        cursor: pointer;
        padding: 10px;
        font-size: 16px;
        margin-right: 10px;
    }

    .tool-bar > div > a {
        color: darkgrey;
    }

    .tool-bar > div:hover, .tool-bar > div:hover i {
        -webkit-transition: color .2s cubic-bezier(0.4, 0, 0.2, 1);
        -moz-transition: color .2s cubic-bezier(0.4, 0, 0.2, 1);
        -ms-transition: color .2s cubic-bezier(0.4, 0, 0.2, 1);
        -o-transition: color .2s cubic-bezier(0.4, 0, 0.2, 1);
        transition: color .2s cubic-bezier(0.4, 0, 0.2, 1);
        color: var(--md-theme-default-accent, #ff5252) !important;
    }

    .overview {
        flex: 1;
        padding: 10px;
        position: relative;
    }

    .comment {
        color: darkgrey;
    }

    .movie-params-table {
        padding-right: 20px;
        padding-top: 30px;
        display: flex;
        justify-content: flex-end;
    }

    .movie-params-table td {
        padding-top: 5px;
        padding-bottom: 5px;
        font-size: 12px;
    }
    
    .table-icons {
        color: #26a69a;
    }

    .movie-body {
        display: flex;
        width: 100%;
        padding-top: 20px;
    }

    .actor {
        width: 185px;
        height: 278px;
        background-size: cover;
        background-position: center center;
        position: relative;
    }

    .actor-name {
        position: absolute;
        width: calc(100% - 20px);
        left: 0;
        bottom: 0;
        padding: 10px;
        font-size: 30px;
        color: white;
        background-color: rgba(0, 0, 0, 0.51);
    }
    
    .add-actor {
        width: 181px;
        height: 274px;
        border: 2px dashed darkgrey;
        -webkit-border-radius: 1rem;
        -moz-border-radius: 1rem;
        border-radius: 1rem;
        color: darkgrey;
        cursor: pointer;
    }

    .add-actor:hover {
        -webkit-transition: border, color .2s ease-in-out;
        -moz-transition: border, color .2s ease-in-out;
        -ms-transition: border, color .2s ease-in-out;
        -o-transition: border, color .2s ease-in-out;
        transition: border, color .2s ease-in-out;
        border: 2px dashed #26a69a;
        color: #26a69a;
    }

    #actors {
        margin-top: 20px;
    }

    #retrieve-modal .modal-content {
        min-height: 520px;
    }

    #retrieve-state {
        margin-top: 10px;
    }

    .shape-slider .s2 span {
        color: darkgrey;
    }

    #back-button {
        position: absolute;
        top: 0;
        left: 0;
        padding: 15px;
        color: #cecece;
        font-size: 42px;
        -webkit-transition: background-color .3s, color .3s;
        -moz-transition: background-color .3s, color .3s;
        -ms-transition: background-color .3s, color .3s;
        -o-transition: background-color .3s, color .3s;
        transition: background-color .3s, color .3s;
    }
    
    #back-button:hover {
        color: white;
        background-color: rgba(0, 0, 0, 0.67);
    }

    .hover-effect {
        transition: .3s cubic-bezier(.4,0,.2,1);
        transition-property: background-color,font-weight;
        will-change: background-color,font-weight;
        cursor: pointer;
    }

    .hover-effect:hover {
        background-color: var(--md-theme-default-highlight-on-background, rgba(0,0,0,0.08));
    }

    #helper-text {
        color: #F44336;
    }

    .invalid {
        border-bottom: 1px solid #F44336;
        -webkit-box-shadow: 0 1px 0 0 #F44336;
        -moz-box-shadow: 0 1px 0 0 #F44336;
        box-shadow: 0 1px 0 0 #F44336;
    }

</style>