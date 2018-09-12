<template>
    <div :id="identifier" style="position: relative; width: 100%; display: flex; align-items: center; justify-content: center; padding-bottom: 1px">
        <loader v-show="loading"></loader>
    </div>
</template>

<script>
    export default {
        props: ['toDispatch', 'identifier', 'loading', 'nextPageUrl'],
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
                if (!this.loading && this.nextPageUrl) {
                    this.$store.dispatch(this.toDispatch);
                }
            }
        }
    }
</script>

<style scoped>

</style>