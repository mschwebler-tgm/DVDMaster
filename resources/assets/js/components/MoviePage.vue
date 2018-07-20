<template>
    <div class="container">
        <user-modal @userSelected="selectUser"></user-modal>
        <div id="retrieve-modal" class="modal">
            <div class="modal-content">
                <h4>Retrieve Movie</h4>
                <hr>
                <template v-if="movie && movie.rented_by.length > 0">
                    Borrowed by {{ movie.rented_by[0].name }} {{ rentalTime }}
                </template>

                <div class="row" style="margin-top: 50px; margin-bottom: 25px;">
                    <div class="col s2"></div>
                    <div class="col s8">
                        <h5>Date</h5>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 70px;">
                    <div class="col s2"></div>
                    <div class="col s4">
                        <input type="text" class="datepicker" id="retrieve-date" v-model="retrieveDate">
                        <label for="retrieve-date">Date</label>
                    </div>
                    <div class="col s4">
                        <a class="waves-effect waves-light btn" @click="chooseCurrentRetrievalDate()">Now</a>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 25px;">
                    <div class="col s2"></div>
                    <div class="col s8">
                        <h5>Quality</h5>
                    </div>
                </div>
                <div class="row shape-slider">
                    <div class="col s2 center-align">
                        <span>DVD lost</span>
                    </div>
                    <div class="col s8">
                        <div id="retrieve-state"></div>
                    </div>
                    <div class="col s2 center-align">
                        <span>Original</span>
                    </div>
                </div>
                <div class="row" style="margin-top: 60px;">
                    <div class="col s2"></div>
                    <div class="col s8">
                        <a class="waves-effect waves-light btn right" @click="retrieveMovie">Save</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row z-depth-5" v-show="!loading && movie">
            <div class="col s12 no-padding" v-if="movie">
                <div class="backdrop-image"
                     :style="{ 'backgroundImage': 'url(' + $root.getImagePath(movie.backdrop_path, 'w1280') + ')'}">
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
                    <div class="tool-bar">
                        <div><i class="material-icons">edit</i> Edit</div>
                        <div>
                            <template v-if="movie">
                                <template v-if="movie.rented_by.length === 0">
                                    <a class="modal-trigger" href="#user-modal"><i class="material-icons">assignment_ind</i> Borrow</a>
                                </template>
                                <template v-else>
                                    <a class="modal-trigger" href="#retrieve-modal"><i class="material-icons">assignment_ind</i> Borrowed by {{ movie.rented_by[0].name }}</a>
                                </template>
                            </template>
                        </div>
                        <div class="dropdown-trigger" data-target="lastSeenDropDown">
                            <i class="material-icons">movie</i> Just seen
                            <template v-if="movie && movie.last_seen">(last: {{ lastSeen }})</template>
                        </div>
                        <ul id='lastSeenDropDown' class='dropdown-content'>
                            <li><a href="#" @click="updateLastSeen('now')"><i class="material-icons">access_time</i> Now</a></li>
                            <li><a href="#" @click="pickLastSeen"><i class="material-icons">calendar_today</i>Pick date</a></li>
                            <li><a href="#" @click="updateLastSeenCustom(6, 'days')">1 week ago</a></li>
                            <li><a href="#" @click="updateLastSeenCustom(1, 'months')">1 month ago</a></li>
                            <li><a href="#" @click="updateLastSeenCustom(1, 'years')">1 year ago</a></li>
                        </ul>
                        <div style="margin-left: auto; margin-right: 0;" @click="deleteMovie"><i class="material-icons">delete</i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12">
                <div class="movie-body">
                    <div class="poster-spacer movie-params-table">
                        <div style="width: 185px;"> <!-- width of poster -->
                            <table class="striped">
                                <tbody>
                                    <tr v-for="row in tableContents">
                                        <td v-html="row" class="right-align"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="overview">
                        <template v-if="movie && movie.overview">
                            {{ movie.overview }}
                            <br><br>
                            <div class="right comment">
                                {{ movie.comment ? '*' + movie.comment : '' }}
                            </div>
                        </template>
                        <div class="genres" v-if="movie && movie.genres">
                            <div class="chip" v-for="genre in movie.genres">
                                {{ genre.name }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12 no-padding" id="actors">   <!-- actors -->
                <swiper :options="swiperOption" v-if="readyToShowActors && movie && movie.actors">
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
            </div>
        </div>
        <div v-if="!loading && !movie">
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
        <input type="text" class="datepicker" id="last-seen-date-picker" style="display: none;" @change="updateLastSeen($event.target.value)">
    </div>
</template>

<script>
    import moment from 'moment';
    import '../../../../node_modules/swiper/dist/css/swiper.css';
    import {swiper, swiperSlide} from 'vue-awesome-swiper';
    import StarRating from 'vue-star-rating';

    export default {
        props: ['id'],
        data() {
            return {
                loading: true,
                movie: null,
                swiperOption: {
                    slidesPerView: window.innerWidth / 230,
                    freeMode: true,
                    spaceBetween: 50,
                },
                readyToShowActors: false,
                userModal: null,
                retrieveModal: null,
                retrieveDatepicker: null,
                retrieveDate: null,
                retrieveSlider: null
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
        mounted() {
            this.initM();
            this.retrieveSlider = document.getElementById('retrieve-state');
            noUiSlider.create(this.retrieveSlider, {
                start: 3,
                range: {
                    min: 0,
                    max: 3
                },
                step: 1,
                connect: [true, false],
                format: {
                    to(value) {
                        switch (value) {
                            case 0: return 'DVD was lost';
                            case 1: return 'Bad';
                            case 2: return 'Good';
                            case 3: return 'Original';
                        }
                        return value;
                    },
                    from: value => { return value }
                }
            });
        },
        methods: {
            initM() {
                let elems = document.querySelectorAll('.dropdown-trigger');
                M.Dropdown.init(elems, {
                    constrainWidth: false
                });
                elems = document.querySelectorAll('.datepicker');
                let currentDate = new Date();
                let instances = M.Datepicker.init(elems, {
                    autoclose: true,
                    yearRange: [(new Date()).getFullYear() - 3, (new Date()).getFullYear()],
                    firstDay: 1,
                    format: 'yyyy-mm-dd',
                    disableDayFn: date => {
                        return date > currentDate;
                    }
                });
                this.retrieveDatepicker = instances[0];
                this.waitForEl('#actors', el => {
                    this.swiperOption.slidesPerView = el.width() / 185;
                    this.readyToShowActors = true;
                });
                elems = document.querySelectorAll('.modal');
                instances = M.Modal.init(elems, {});
                this.userModal = instances[0];
                this.retrieveModal = instances[1];
            },
            deleteMovie() {
                axios.get('/api/movie/' + this.id + '/delete').then(() => {
                    M.toast({html: 'Movie deleted', classes: 'complete-toast'});
                    this.$root.$router.go(-1);
                }).catch(() => {
                    M.toast({html: 'Error while deleting movie', classes: 'complete-toast'});
                });
            },
            updateLastSeen(date) {
                axios.get('/api/movie/' + this.id + '/lastSeen/' + date).then(res => {
                    this.movie.last_seen = res.data;
                    M.toast({html: 'Updated last seen.', classes: 'complete-toast'});
                }).catch(() => {
                    M.toast({html: 'Error while updating last seen', classes: 'complete-toast'});
                });
            },
            updateLastSeenCustom(amount, type) {
                this.updateLastSeen(moment().subtract(amount, type).format('YYYY-MM-DD'));
            },
            pickLastSeen() {
                $('#last-seen-date-picker').trigger('click');
            },
            waitForEl(selector, callback) {
                let el = $(selector);
                if (el.width()) {
                    callback(el);
                } else {
                    setTimeout(() => {
                        this.waitForEl(selector, callback);
                    }, 100);
                }
            },
            selectUser(user) {
                this.userModal.close();
                axios.post('/api/movie/' + this.movie.id + '/borrowTo/' + user.id).then(res => {
                    Vue.set(this.movie, 'rented_by', [user]);
                    Vue.set(this.movie, 'pending_rental', [res.data]);
                    M.toast({html: 'Borrowed to: ' + user.name, classes: 'complete-toast'});
                })
            },
            retrieveMovie() {
                let quality = this.retrieveSlider.noUiSlider.get();
                switch (quality) {
                    case 'DVD was lost': quality = 'lost';
                        break;
                    case 'Bad': quality = 'bad';
                        break;
                    case 'Good': quality = 'good';
                        break;
                    case 'Original': quality = 'original';
                        break;
                }
                axios.post('/api/movie/' + this.movie.id + '/retrieve', {
                    quality,
                    date: this.retrieveDate
                }).then(res => {
                    Vue.set(this.movie, 'rented_by', []);
                    Vue.set(this.movie, 'pending_rental', []);
                    this.retrieveModal.close();
                    M.toast({html: 'Movie retrieved', classes: 'complete-toast'});
                });
            },
            chooseCurrentRetrievalDate() {
                this.retrieveDatepicker.setDate(new Date());
                this.retrieveDate = this.retrieveDatepicker.toString();
            }
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
            rentalTime() {
                if (!this.movie || !this.movie.pending_rental[0]) {
                    return '';
                }

                let date = moment(this.movie.pending_rental[0].created_at);
                return date.fromNow();
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

    #retrieve-modal .datepicker-modal {
        min-width: 545px;
    }

    #retrieve-state {
        margin-top: 10px;
    }

    .shape-slider .s2 span {
        color: darkgrey;
    }

</style>