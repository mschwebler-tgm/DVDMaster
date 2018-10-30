<template>
    <div class="container">
        <template v-if="series && !loading">
            <router-view :series="series"></router-view>
        </template>
        <template v-if="!series && !loading">
            <div class="center" style="padding: 4em">
                <h5>Sorry, the series you requested could not be found.</h5>
                <a href="#" @click="$root.$router.go(-1)">Go back</a>
            </div>
        </template>
        <template v-if="loading">
            <div class="container center">
                <div class="preloader-wrapper active pre-loader">
                    <div class="spinner-layer spinner-red-only">
                        <div class="circle-clipper left">
                            <div class="circle"></div>
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
    </div>
</template>

<script>
    export default {
        props: ['id'],
        data() {
            return {
                loading: false
            }
        },
        created() {
            if (this.$store.getters.SERIES_GET.id != this.id) { this.loading = true; }
            this.$store.dispatch('SERIES_ACTION_GET_BY_ID', this.id)
                .then(() => this.loading = false)
                .catch(() => this.loading = false);
        },
        computed: {
            series() {
                return this.$store.getters.SERIES_GET.id == this.id ? this.$store.getters.SERIES_GET : null;
            }
        }
    }
</script>

<style scoped>

</style>