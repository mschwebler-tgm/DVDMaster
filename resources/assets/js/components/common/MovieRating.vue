<template>
    <div class="star-ratings" @click="stopPropagination($event)">
        <star-rating :increment="0.5" style="z-index: 1000;" class="custom-rating" inactive-color="white"
                     active-color="#FFD700" v-model="movie.custom_rating" :show-rating="false"
                     :border-width="0" :star-size="40" @click="$emit('newCustomRating', movie.custom_rating)"></star-rating>
        <star-rating :increment="0.01" :rating="movie.vote_average/2" :show-rating="false" class="tmdb-rating"
                     active-color="#aaaaaa" inactive-color="#efefef" :border-width="0"
                     v-if="!movie.custom_rating" :star-size="40"></star-rating>
    </div>
</template>

<script>
    import StarRating from 'vue-star-rating';

    export default {
        props: ['movie', ],
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

    .star-ratings:hover .custom-rating {
        display: flex;
    }

    .star-ratings:hover .tmdb-rating {
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