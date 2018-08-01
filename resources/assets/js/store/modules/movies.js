const state = {
    movies: [],
    movie: null
};

const actions = {
    MOVIES_ACTION_GET_ALL ({commit}) {
        axios.get('/api/movies').then(res => {
            commit('MOVIES_COMMIT_SET_LIST', res.data);
        });
    },
    MOVIES_ACTION_SEARCH ({commit}, query) {
        axios.get('/api/customSearch/movies?query=' + query).then(res => {
            commit('MOVIES_COMMIT_SET_LIST', res.data);
        });
    },
    MOVIES_ACTION_GET_BY_ID ({commit}, id) {
        commit('MOVIES_COMMIT_SET', null);
        axios.get('/api/movie/' + id).then(res => {
            commit('MOVIES_COMMIT_SET', res.data);
        }).catch(() => {
            commit('MOVIES_COMMIT_SET', 404);
        });
    }
};

const mutations = {
    MOVIES_COMMIT_SET_LIST (state, movies) {
        state.movies = movies;
    },
    MOVIES_COMMIT_SET (state, movie) {
        state.movie = movie;
    }
};

const getters = {
    MOVIES_GET_ALL: state => state.movies,
    MOVIES_GET: state => state.movie,
};

export default {
    state,
    actions,
    mutations,
    getters
};