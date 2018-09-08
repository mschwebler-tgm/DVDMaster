<template>
    <div id="retrieval-modal">
        <md-dialog :md-active.sync="showModal">
            <md-dialog-content>
                <md-steppers :md-active-step.sync="active" md-linear>
                    <md-step id="user" md-label="Recommendation" :md-description="like !== null ? (like ? 'Like' : 'Dislike') : ''" :md-done.sync="likeSelected">
                        <div class="custom-step">
                            <br>
                            <span class="md-display-1">Did the person like it?</span>
                            <br>
                            <br>
                            <br>
                            <div class="flex flex-justify-center">
                                <div style="flex: 1;" class="flex flex-justify-end"><md-content class="like-emoji md-accent" @click="dislikeIt"><md-icon class="md-size-5x">sentiment_very_dissatisfied</md-icon></md-content></div>
                                <div style="flex: 1;" class="flex flex-justify-start"><md-content class="like-emoji md-accent" @click="likeIt"><md-icon class="md-size-5x">sentiment_satisfied_alt</md-icon></md-content></div>
                            </div>
                            <md-button class="md-accent step-button" @click="setDone('likeSelected', 'date')" style="float: right;">Skip</md-button>
                        </div>
                    </md-step>
                    <md-step id="date" class="custom-step" :md-description="formattedDate" md-label="Retrieval date" :md-done.sync="dateSelected">
                        <div class="custom-step">
                            <br>
                            <span class="md-display-1">Choose a retrieval date</span>
                            <br>
                            <br>
                            <br>
                            <div class="flex flex-justify-center flex-align-center">
                                <div class="flex" style="flex-direction: column">
                                    <div>
                                        <md-button class="md-accent md-raised" @click="setDate">Today</md-button>
                                        <md-button class="md-accent md-raised" @click="setDate(7, 'days')">Last Week</md-button>
                                        <md-button class="md-accent md-raised" @click="setDate(1, 'month')">Last Month</md-button>
                                    </div>
                                    <div style="padding-left: 10px; padding-right: 10px">
                                        <md-datepicker v-model="date" md-immediately>
                                            <label>Select date</label>
                                        </md-datepicker>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <md-button class="md-raised md-accent step-button" @click="setDone('dateSelected', 'quality')" style="float: right">Continue</md-button>
                        </div>
                    </md-step>
                    <md-step id="quality" class="custom-step" md-label="Quality" :md-description="quality || ''" :md-done.sync="qualitySelected">
                        <div class="custom-step">
                            <br>
                            <span class="md-display-1">In which shape was the DVD?</span>
                            <br>
                            <br>
                            <br>
                            <div style="height: 100px" class="flex flex-justify-center flex-align-center">
                                <vue-slider v-model="quality" v-bind="sliderOptions" ref="slider">
                                    <div class="vue-slider-tooltip" slot="tooltip" slot-scope="{ value }"
                                         style="background-color: var(--md-theme-default-accent, #448aff);
                                                border: 1px solid var(--md-theme-default-accent, #448aff)">
                                        {{ value }}
                                    </div>
                                    <div slot="label" slot-scope="{ label, active }">
                                        <span class="vue-slider-piecewise-label" v-if="active" style="color: var(--md-theme-default-accent, #448aff);">{{ label }}</span>
                                        <span class="md-caption" v-else style="
                                        position: absolute;
                                        display: inline-block;
                                        top: 100%;
                                        left: 50%;
                                        white-space: nowrap;
                                        font-size: 12px;
                                        transform: translate(-50%,8px);
                                        visibility: visible;">{{ label }}</span>
                                    </div>
                                </vue-slider>
                            </div>

                            <md-button class="md-raised md-primary step-button" @click="setDone('qualitySelected')" style="float: right">Done</md-button>
                        </div>
                    </md-step>
                </md-steppers>
            </md-dialog-content>
        </md-dialog>
    </div>
</template>

<script>
    import moment from 'moment';
    import vueSlider from 'vue-slider-component';

    export default {
        name: 'RetrievalModal',
        props: ['show'],
        data() {
            return {
                invalid: false,
                username: '',
                showModal: false,
                active: 'user',
                likeSelected: false,
                dateSelected: false,
                qualitySelected: false,
                like: null,
                date: null,
                quality: 'Original',
                sliderOptions: {
                    width: '80%',
                    tooltip: 'always',
                    piecewise: true,
                    piecewiseLabel: true,
                    data: [
                        'DVD lost',
                        'Bad shape',
                        'Some damage',
                        'Minor scratches',
                        'Original',
                    ],
                    piecewiseStyle: {
                        'backgroundColor': '#ccc',
                        'visibility': 'visible',
                        'width': '12px',
                        'height': '12px'
                    },
                    piecewiseActiveStyle: {
                        'backgroundColor': 'var(--md-theme-default-accent, #448aff)',
                    },
                    labelActiveStyle: {
                        'color': 'var(--md-theme-default-accent, #448aff)',
                        'border': '1px solid var(--md-theme-default-accent, #448aff)'
                    },
                    processStyle: {
                        'background-color': 'var(--md-theme-default-accent, #448aff)'
                    }
                }
            }
        },
        created() {
            this.$store.dispatch('USERS_ACTION_GET_All_EXCEPT_ME');
            this.showModal = this.show;
        },
        methods: {
            setDone(id, index) {
                this[id] = true;

                if (index) {
                    this.active = index
                }

                if (this.likeSelected && this.dateSelected && this.qualitySelected) {
                    this.showModal = false;
                    this.$emit('retrieved', {
                        like: this.like,
                        date: this.date,
                        quality: this.quality
                    });
                }
            },
            setDate(amount, unit) {
                if (!amount) {
                    this.date = moment().format('YYYY-MM-DD');
                } else {
                    this.date = moment().subtract(amount, unit).format('YYYY-MM-DD');
                }
            },
            likeIt() {
                this.like = true;
                this.setDone('likeSelected', 'date');
            },
            dislikeIt() {
                this.like = false;
                this.setDone('likeSelected', 'date');
            }
        },
        watch: {
            username() {
                this.invalid = this.usernameIsInvalid();
            },
            showModal(show) {
                this.$emit('update:show', show);
            },
            show(show) {
                this.showModal = show;
            },
            active(state) {
                if (state === 'quality') {
                    setTimeout(this.$refs.slider.refresh, 1000);
                }
            }
        },
        computed: {
            users() {
                return this.$store.getters.USERS_GET_ALL;
            },
            formattedDate() {
                return this.date ? moment(this.date).format('YYYY-MM-DD') : '';
            }
        },
        components: {
            vueSlider
        }
    }
</script>

<style scoped>
    .custom-step {
        min-height: 350px;
        position: relative;
    }

    .like-emoji {
        padding: 30px;
        margin: 5px;
        cursor: pointer;
        -webkit-transition: all .3s;
        -moz-transition: all .3s;
        -ms-transition: all .3s;
        -o-transition: all .3s;
        transition: all .3s;
        border: 10px solid white;
    }
    
    .like-emoji:hover {
        -webkit-transform: scale(1.2);
        -moz-transform: scale(1.2);
        -ms-transform: scale(1.2);
        -o-transform: scale(1.2);
        transform: scale(1.2);
    }

    .step-button {
        position: absolute;
        bottom: 25px;
        right: 25px;
    }

</style>