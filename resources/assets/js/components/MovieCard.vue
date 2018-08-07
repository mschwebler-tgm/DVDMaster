<template>
    <div class="card pointer" @click="$root.$router.push('/movie/' + movie.id)">
        <div class="card-image">
            <img :src="$root.getImagePath(movie.backdrop_path, 'w500')">
            <span class="card-title">{{ movie.title }}</span>
        </div>
        <div class="card-content movie-overview">
            <p>{{ movie.overview }}</p>
        </div>
        <div class="card-action">
            <a href="#">
                <i class="material-icons">trending_up</i>&nbsp;&nbsp;
                {{ movie.vote_average }}
                <span class="text-muted">&nbsp;({{ movie.vote_count}})</span>
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['movie']
    }
</script>

<style scoped>

    .movie-overview {
        width: 100%;
        max-height: 120px;
        overflow: hidden;
        -ms-text-overflow: ellipsis;
        text-overflow: ellipsis;
    }

</style>