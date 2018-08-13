<template>
    <transition name="filters">
        <md-content class="filters md-elevation-2" v-show="show">
            <div class="content-wrapper">
                <div class="content">
                    <div class="tag-filters">
                        <div class="flex flex-align-center">
                            <md-icon>bookmark</md-icon>&nbsp;&nbsp;<genres-input classes="custom-tag filter-tag"></genres-input> <span class="md-caption">Genres</span>
                        </div>
                        <div class="flex flex-align-center">
                            <md-icon>person</md-icon>&nbsp;&nbsp;<actors-input classes="custom-tag filter-tag"></actors-input> <span class="md-caption">Actors</span>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <table>
                        <tr>
                            <td>
                                <span class="icon-filters"><md-icon>import_export</md-icon> Borrowed</span>
                            </td>
                            <td>
                                <span class="icon-filters"><md-icon>album</md-icon> Blue Ray</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="icon-filters"><md-icon>event</md-icon> True story</span>
                            </td>
                            <td>
                                <span class="icon-filters"><md-icon>book</md-icon> Based on book</span>
                            </td>
                        </tr>
                    </table>




                </div>
                <div class="close" @click="$parent.toggleFilters()">
                    <md-icon>expand_less</md-icon>
                </div>
            </div>
        </md-content>
    </transition>
</template>

<script>
    export default {
        props: ['show'],
        data() {
            return {
                boolFilters: []
            }
        }
    }
</script>

<style scoped>

    .filters {
        height: 100px;
    }

    .close {
        margin-left: auto;
        padding: 10px;
        cursor: pointer;
    }

    .content-wrapper {
        display: flex;
        height: 100%;
    }
    .content {
        padding: 15px 15px 15px 30px;
        display: flex;
        align-items: center;
        height: 100%;
    }

    .content:first-child {
        flex: 2;
    }

    .content:nth-child(2) {
        flex: 1;
        flex-wrap: wrap;
    }

    .tag-filters {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: space-around;
        height: 100%;
    }

    .tag-filters span {
        -webkit-transition: all 1s;
        -moz-transition: all 1s;
        -ms-transition: all 1s;
        -o-transition: all 1s;
        transition: all 1s;
    }

    .icon-filters {
        padding: 5px;
    }

    /* Animations */

    .filters-enter-active {
        height: 100px;
        -webkit-transition: height .5s;
        -moz-transition: height .5s;
        -ms-transition: height .5s;
        -o-transition: height .5s;
        transition: height .5s;
        overflow: hidden;
    }

    .filters-leave-active {
        height: 0;
        -webkit-transition: height .5s;
        -moz-transition: height .5s;
        -ms-transition: height .5s;
        -o-transition: height .5s;
        transition: height .5s;
        overflow: hidden;
    }

    .filters-leave {

    }

    .filters-enter {
        height: 0;
    }

</style>