const state = {
    movies: {
        data: []
    },
    movie: {},
    filter: {}
};

const actions = {
    MOVIES_ACTION_GET_FIRSTPAGE ({commit}) {
        return new Promise((resolve, reject) => {
            axios.get('/api/movies').then(res => {
                commit('MOVIES_COMMIT_SET_MOVIESDATA', res.data);
                resolve();
            }).catch(reject);
        });
    },
    MOVIES_ACTION_GET_LOADNEXTPAGE ({state, commit}) {
        return new Promise((resolve, reject) => {
            if (!state.movies.next_page_url) { reject(0) }
            axios.get(state.movies.next_page_url).then(res => {
                commit('MOVIES_COMMIT_APPEND_MOVIESDATA', res.data);
                resolve();
            }).catch(reject);
        });
    },
    MOVIES_ACTION_SEARCH ({commit, state}) {
        return new Promise((resolve, reject) => {
            axios.get('/api/customSearch/movies', {params: state.filter}).then(res => {
                commit('MOVIES_COMMIT_SET_MOVIESDATA', res.data);
                resolve();
            }).catch(reject);
        });
    },
    MOVIES_ACTION_GET_BY_ID ({commit}, id) {
        return new Promise((resolve, reject) => {
            axios.get('/api/movie/' + id).then(res => {
                commit('MOVIES_COMMIT_SET', res.data);
                resolve();
            }).catch(() => {
                reject();
            });
        });
    },
    MOVIES_ACTION_SAVE ({commit}, payload) {
        axios.post('/api/movie/', payload).then(res => {
            M.toast({ html: 'Movie saved', classes: 'complete-toast' });
        }).catch(() => {
            M.toast({ html: 'Error while saving movie', classes: 'complete-toast' });
        });
    },
    MOVIES_ACTION_UPDATE ({commit}, {payload, id}) {
        axios.post('/api/movie/' + id + '/update', payload).then(res => {
            M.toast({ html: 'Movie saved', classes: 'complete-toast' });
        }).catch(() => {
            M.toast({ html: 'Error while saving movie', classes: 'complete-toast' });
        });
    }
};

const mutations = {
    MOVIES_COMMIT_SET_MOVIESDATA (state, data) {
        state.movies = data;
    },
    MOVIES_COMMIT_APPEND_MOVIESDATA (state, data) {
        data.data = state.movies.data.concat(data.data);
        state.movies = data;
    },
    MOVIES_COMMIT_SET (state, movie) {
        state.movie = movie;
    },
    MOVIES_COMMIT_FILTER_UPDATE (state, {type, data}) {
        state.filter[type] = data;
    }
};

const getters = {
    MOVIES_GET_ALL: state => state.movies.data,
    MOVIES_GET_NEXT_PAGE_URL: state => state.movies.next_page_url,
    MOVIES_GET: state => state.movie,
};

export default {
    state,
    actions,
    mutations,
    getters
};