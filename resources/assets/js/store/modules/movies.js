const state = {
    movies: [],
};

const actions = {
    MOVIES_ACTION_GET ({commit}) {
        axios.get('/api/movies').then(res => {
            commit('MOVIES_COMMIT_SET', res.data);
        });
    },
    MOVIES_ACTION_SEARCH ({commit}, query) {
        axios.get('/api/customSearch/movies?query=' + query).then(res => {
            commit('MOVIES_COMMIT_SET', res.data);
        });
    }
};

const mutations = {
    MOVIES_COMMIT_SET (state, movies) {
        state.movies = movies;
    }
};

const getters = {
    MOVIES_GET_ALL: state => {return state.movies},
};

export default {
    state,
    actions,
    mutations,
    getters
};