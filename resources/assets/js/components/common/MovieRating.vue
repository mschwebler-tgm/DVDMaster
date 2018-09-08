<template>
    <div class="star-ratings" :class="{ enable: $root.isAdmin }" @click="stopPropagination($event)">
        <star-rating :increment="0.5" style="z-index: 1000;" class="custom-rating" inactive-color="white"
                     active-color="var(--md-theme-default-accent, #FFD700)" v-model="movie.custom_rating" :show-rating="false"
                     :border-width="0" :star-size="starSize || 40" @click="$emit('newCustomRating', movie.custom_rating)"
                     glow-color="rgba(0,0,0,0)" :style="showCustomRating ? 'display: flex' : ''" :readOnly="!$root.isAdmin"></star-rating>
        <star-rating :increment="0.01" :rating="movie.vote_average/2" :show-rating="false" class="tmdb-rating"
                     active-color="#aaaaaa" inactive-color="#efefef" :border-width="0"
                     v-if="!movie.custom_rating" :star-size="starSize || 40" glow-color="rgba(0,0,0,0)" :readOnly="!$root.isAdmin"></star-rating>
    </div>
</template>

<script>
    import StarRating from 'vue-star-rating';

    export default {
        props: ['movie', 'showCustomRating', 'starSize'],
        watch: {
            'movie.custom_rating': function(newVal) {
                this.$emit('newCustomRating', newVal);
            }
        },
        methods: {
            stopPropagination(event) {
                event.stopPropagation();
            }
        },
        components: {
            StarRating
        }
    }
</script>

<style scoped>
    .star-ratings {
        position: relative;
    }

    .star-ratings.enable:hover .custom-rating {
        display: flex;
    }

    .star-ratings.enable:hover .tmdb-rating {
        display: none;
    }

    .custom-rating {
        display: none;
    }

    .tmdb-rating {
        position: absolute;
        top: 0;
    }
</style>