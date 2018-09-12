<template>
    <transition name="filters">
        <md-content class="filters md-elevation-2" v-show="show">
            <tags-input type="actors" image-key="profile_path"></tags-input>
            <tags-input type="genres"></tags-input>
        </md-content>
    </transition>
</template>

<script>
    export default {
        props: ['show'],
        data() {
            return {
                boolFilters: [],
            }
        }
    }
</script>

<style scoped>
    .left {
        float: left;
    }
</style>