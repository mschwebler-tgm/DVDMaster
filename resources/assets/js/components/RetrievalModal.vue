<template>
    <div id="retrieval-modal">
        <md-dialog :md-active.sync="showModal">
            <md-dialog-content>
                <md-steppers :md-active-step.sync="active" md-linear>
                    <md-step id="user" md-label="Select User" :md-done.sync="userSelected">
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias doloribus eveniet quaerat modi cumque quos sed, temporibus nemo eius amet aliquid, illo minus blanditiis tempore, dolores voluptas dolore placeat nulla.</p>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias doloribus eveniet quaerat modi cumque quos sed, temporibus nemo eius amet aliquid, illo minus blanditiis tempore, dolores voluptas dolore placeat nulla.</p>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias doloribus eveniet quaerat modi cumque quos sed, temporibus nemo eius amet aliquid, illo minus blanditiis tempore, dolores voluptas dolore placeat nulla.</p>
                        <md-button class="md-raised md-primary" @click="setDone('userSelected', 'date')">Continue</md-button>
                    </md-step>

                    <md-step id="date" md-label="Retrieval date" :md-done.sync="dateSelected">
                        <br>
                        <span class="md-display-1">Choose a retrieval date</span>
                        <br>
                        <br>
                        <div class="flex">
                            <div class="flex" style="flex-direction: column">
                                <div>
                                    <md-button class="md-raised" @click="setDate">Today</md-button>
                                    <md-button class="md-raised" @click="setDate(7, 'days')">Last Week</md-button>
                                    <md-button class="md-raised" @click="setDate(1, 'month')">Last Month</md-button>
                                </div>
                                <div style="padding-left: 10px; padding-right: 10px">
                                    <md-datepicker v-model="date" md-immediately>
                                        <label>Select date</label>
                                    </md-datepicker>
                                </div>
                            </div>
                        </div>
                        <br>
                        <md-button class="md-raised md-primary" @click="setDone('dateSelected', 'quality')">Continue</md-button>
                    </md-step>

                    <md-step id="quality" md-label="Quality" md-description="Optional" :md-done.sync="qualitySelected">
                        <div style="height: 100px" class="flex flex-justify-center flex-align-center">
                            <vue-slider v-model="quality"
                                        v-bind="options">

                            </vue-slider>
                        </div>

                        <md-button class="md-raised md-primary" @click="setDone('qualitySelected')">Done</md-button>
                    </md-step>
                </md-steppers>
            </md-dialog-content>
            <md-dialog-actions>
                <md-button class="md-accent" @click="showModal = false">Cancel</md-button>
            </md-dialog-actions>
        </md-dialog>
    </div>
</template>

<script>
    import moment from 'moment';
    import vueSlider from 'vue-slider-component/dist/index';

    export default {
        name: 'RetrievalModal',
        props: ['show'],
        data() {
            return {
                width: '80%',
                invalid: false,
                username: '',
                showModal: false,
                active: 'user',
                userSelected: false,
                dateSelected: false,
                qualitySelected: false,
                date: null,
                quality: 3,
                sliderOptions: {
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
                        'backgroundColor': '#3498db'
                    },
                    labelActiveStyle: {
                        'color': '#3498db'
                    }
                }
            }
        },
        created() {
            this.sliderOptions = {

            };
            this.$store.dispatch('USERS_ACTION_GET_All_EXCEPT_ME');
            this.showModal = this.show;
        },
        methods: {
            setDone(id, index) {
                this[id] = true;

                if (index) {
                    this.active = index
                }
            },
            setDate(amount, unit) {
                if (!amount) {
                    this.date = moment().format('YYYY-MM-DD');
                } else {
                    this.date = moment().subtract(amount, unit).format('YYYY-MM-DD');
                }
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
            }
        },
        computed: {
            users() {
                return this.$store.getters.USERS_GET_ALL;
            }
        },
        components: {
            vueSlider
        }
    }
</script>

<style scoped>
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