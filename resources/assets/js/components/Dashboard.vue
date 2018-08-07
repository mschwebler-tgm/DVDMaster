<template>
    <div class="dashboard-wrapper z-depth-3">
        <template v-if="stats">
        <div class="dashboard">
            <div class="item">
                <div>
                    <!--<i class="material-icons">theaters</i>&nbsp;&nbsp;-->
                    <i class="material-icons">album</i>&nbsp;&nbsp;
                    <span class="counter">{{ stats.movieCount }}</span>
                </div>
                <div class="label">Movies</div>
            </div>
            <div class="item">
                <div>
                    <i class="material-icons">movie</i>&nbsp;&nbsp;
                    <span class="counter">{{ stats.seriesCount }}</span>
                </div>
                <div class="label">Series</div>
            </div>
            <div class="item">
                <div>
                    <i class="material-icons">import_export</i>&nbsp;&nbsp;
                    <span class="counter">{{ stats.pendingRentalsCount }}</span>
                </div>
                <div class="label">Pending rentals</div>
            </div>
        </div>
        <div class="dashboard">
            <div class="item small">
                <div>
                    <i class="material-icons">playlist_add</i>&nbsp;&nbsp;
                    <span class="counter">{{ stats.newMoviesCount }}</span>&nbsp;
                    <div class="label">new movies last week</div>
                </div>
            </div>
            <div class="item small">
                <div class="tooltipped" data-position="bottom" data-tooltip="Rentals older than 1 month">
                    <i class="material-icons">watch_later</i>&nbsp;&nbsp;
                    <span class="counter">{{ stats.oldRentals }}</span>&nbsp;
                    <div class="label">old rentals</div>
                </div>
            </div>
        </div>
        </template>
        <template v-else>
            <div style="position: relative; padding: 30px">
                <loader></loader>
            </div>
        </template>
    </div>
</template>

<script>
    export default {
        created() {
            this.$store.dispatch('STATS_ACTION_GET_ALL').then(() => {
                $('.counter').each(function () {
                    $(this).prop('Counter', 0).animate({
                        Counter: $(this).text()
                    }, {
                        duration: 2500,
                        easing: 'swing',
                        step: function (now) {
                            $(this).text(Math.ceil(now));
                        }
                    });
                });
                let elems = document.querySelectorAll('.tooltipped');
                M.Tooltip.init(elems);
            });
        },
        mounted() {
        },
        computed: {
            stats() {
                return this.$store.getters.STATS_GET_ALL;
            }
        }
    }
</script>

<style scoped>
    .dashboard-wrapper {
        margin-bottom: 20px;
        padding: 30px;
        color: darkgrey;
    }

    .dashboard {
        display: flex;
        justify-content: space-around;
    }

    .dashboard:first-child {
        padding-bottom: 45px;
    }

    .counter {
        font-size: 58px;
    }

    .dashboard .material-icons {
        font-size: 58px;
    }

    .item.small > div:first-child > .material-icons {
        font-size: 20px !important;
    }
    .item.small > div:first-child > .counter {
        font-size: 20px !important;
    }
    .item.small > div:first-child > .label {
        font-size: 20px !important;
    }

    .item {
        -webkit-transition: color .3s;
        -moz-transition: color .3s;
        -ms-transition: color .3s;
        -o-transition: color .3s;
        transition: color .3s;
        cursor: default;
    }
    
    .item > div:first-child {
        display: flex;
        align-items: center;
    }
    
    .item:hover {
        color: #26a69a;
    }

</style>