<template>
    <table>
        <thead>
        <tr>
            <th></th>
            <th>Title</th>
            <th>Genres</th>
            <th class="hide-on-med-and-down">Actors</th>
            <th class="hide-on-small-only">Rating</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="movie in movies" @click="$root.$router.push('/movie/' + movie.id)">
            <td><img :src="$root.getImagePath(movie.poster_path, 'w92')" width="92" height="138"></td>
            <td>{{ movie.title }}</td>
            <td>
                <div class="genre-col">
                    <template v-for="genre in getGenreNames(movie)">
                        <div class="chip">{{ genre }}</div>
                    </template>
                </div>
            </td>
            <td class="hide-on-med-and-down">
                <div class="actor-col">
                    <template v-for="actor in getActorNames(movie)">
                                    <span class="pointer">
                                        {{ actor }}
                                    </span>
                    </template>
                </div>
            </td>
            <td class="hide-on-small-only">
                <movie-rating :movie="movie" @newCustomRating="updateRating(movie, $event)"></movie-rating>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props: ['movies'],
        methods: {
            getGenreNames(movie) {
                let names = [];
                for (let genre of movie.genres) {
                    names.push(genre.name);
                }
                return names;
            },
            getActorNames(movie) {
                let names = [];
                for (let actor of movie.actors) {
                    names.push(actor.name);
                    if (names.length === 3) {
                        break;
                    }
                }
                return names;
            },
            updateRating(movie, rating) {
                console.log(rating);
                axios.post('/api/movie/' + movie.id + '/rate', {rating});
            }
        }
    }
</script>

<style scoped>
    .genre-col {
        max-width: 300px;
    }

    tr:hover {
        background-color: #f5f5f5;
        cursor: pointer
    }
</style>