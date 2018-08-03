<template>
    <div>
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
            <tr>
                <paginator toDispatch="MOVIES_ACTION_GET_LOADNEXTPAGE" identifier="movie-list-view-paginator"></paginator>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: ['movies'],
        methods: {
            getGenreNames(movie) {
                let names = [];
                for (let i = 0; movie.genres && i < movie.genres.length; i++) {
                    names.push(movie.genres[i].name);
                }
                return names;
            },
            getActorNames(movie) {
                let names = [];
                for (let i = 0; movie.actors && i < movie.actors.length; i++) {
                    names.push(movie.actors[i].name);
                }
                return names;
            },
            updateRating(movie, rating) {
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