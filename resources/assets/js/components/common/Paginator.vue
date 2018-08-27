<template>
    <div :id="identifier" style="position: relative; width: 100%; display: flex; align-items: center; justify-content: center;">
        <div class="sk-double-bounce" v-show="loading">
            <div class="sk-child sk-double-bounce1"></div>
            <div class="sk-child sk-double-bounce2"></div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['toDispatch', 'identifier'],
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
        },
        computed: {
            loading() {
                return this.$store.getters.MOVIES_GET_LOADING;
            }
        }
    }
</script>

<style scoped>

</style>