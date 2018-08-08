<template>
    <md-app>
        <md-app-toolbar class="md-primary">
            <div style="width: 100%">
                <div class="md-layout">
                    <div class="md-layout-item md-small-size-5 md-xsmall-hide">
                        <span class="md-title md-small-hide" style="line-height: 48px; vertical-align: center;">DVDMaster</span>
                    </div>
                    <div class="md-layout-item md-small-size-80 md-medium-size-66 md-large-size-66 md-xlarge-size-66">
                        <md-tabs class="md-primary" style="padding: 0;" @md-changed="navigate">
                            <md-tab :id="tab.url" :md-label="tab.label" :key="tab.label" v-for="tab in tabs"></md-tab>
                        </md-tabs>
                    </div>
                    <div class="md-layout-item md-small-size-15 md-xsmall-hide">
                        <slot name="topRight"></slot>
                    </div>
                </div>
            </div>
        </md-app-toolbar>

        <md-app-content>
            <div class="md-layout">
                <div class="md-layout-item md-xsmall-hide md-small-size-5 md-xsmall-hide"></div>
                <div class="md-layout-item md-xsmall-size-100 md-small-size-90 md-medium-size-66 md-large-size-66 md-xlarge-size-66">
                    <slot name="content"></slot>
                </div>
                <div class="md-layout-item md-xsmall-hide md-small-size-5 md-xsmall-hide"></div>
            </div>
        </md-app-content>
    </md-app>
</template>

<script>
    export default {
        data() {
            return {
                tabs: [
                    {label: 'Home', url: '/'},
                    {label: 'Add Movie', url: '/addMovie'},
                    {label: 'Rentals', url: '/rentals'}
                ]
            }
        },
        methods: {
            navigate(event) {
                this.$router.push(event);
            }
        }
    }
</script>

<style scoped>

</style>