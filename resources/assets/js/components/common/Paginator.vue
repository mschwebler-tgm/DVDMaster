<template>
    <div :id="identifier" style="height: 120px; position: relative; width: 100%; display: flex; align-items: center; justify-content: center;">
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
                if (!this.loading && this.$store.getters.MOVIES_GET_NEXT_PAGE_URL) {
                    console.log('loading');
                    this.loading = true;
                    this.$store.dispatch(this.toDispatch).then(() => {
                        this.loading = false;
                    });
                }
            }
        }
    }
</script>

<style scoped>
    #paginator {
        padding-top: 160px;
        position: relative;
    }
</style>