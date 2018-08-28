<template>
    <div :id="identifier" style="position: relative; width: 100%; display: flex; align-items: center; justify-content: center; padding-bottom: 1px">
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
                let scrollContainer = $('#app-content').parent();
                scrollContainer.scroll(() => {
                    // https://stackoverflow.com/questions/16308037/detect-when-elements-within-a-scrollable-div-are-out-of-view
                    let contHeight = scrollContainer.height();
                    let elemTop = paginatorEl.offset().top - scrollContainer.offset().top;
                    let elemBottom = elemTop + paginatorEl.height();
                    let isTotal = (elemTop >= 0 && elemBottom <= contHeight);
                    isTotal && this.paginate();
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