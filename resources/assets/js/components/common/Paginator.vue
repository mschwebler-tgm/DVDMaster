<template>
    <div :id="identifier" style="position: relative; width: 100%; display: flex; align-items: center; justify-content: center;">
        <loader v-show="loading"></loader>
    </div>
</template>

<script>
    export default {
        props: ['toDispatch', 'identifier'],
        data() {
            return {
                loading: false
            }
        },
        mounted() {
            this.initScrollSpy();
        },
        methods: {
            initScrollSpy() {
                let paginatorEl = $('#' + this.identifier);
                let $window = $(window);
                $window.scroll(() => {
                    let top_of_element = paginatorEl.offset().top;
                    let bottom_of_element = paginatorEl.offset().top + paginatorEl.outerHeight();
                    let bottom_of_screen = $window.scrollTop() + window.innerHeight;
                    let top_of_screen = $window.scrollTop();

                    if ((bottom_of_screen > top_of_element) && (top_of_screen < bottom_of_element)) {
                        this.paginate();
                    }
                });
            },
            paginate() {
                if (!this.$store.getters.MOVIES_GET_LOADING && this.$store.getters.MOVIES_GET_NEXT_PAGE_URL) {
                    this.$store.dispatch(this.toDispatch);
                }
            }
        }
    }
</script>

<style scoped>

</style>