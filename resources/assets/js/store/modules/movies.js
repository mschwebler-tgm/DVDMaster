const state = {
    $root: null,
    movies: {
        data: []
    },
    movie: {},
    filter: {},
    loading: false
};

const actions = {
    MOVIES_ACTION_GET_FIRSTPAGE ({state, commit}) {
        state.loading = true;
        return new Promise((resolve, reject) => {
            axios.get('/api/movies').then(res => {
                commit('MOVIES_COMMIT_SET_MOVIESDATA', res.data);
                state.loading = false;
                resolve();
            }).catch(() => {
                state.loading = false;
                reject();
            });
        });
    },
    MOVIES_ACTION_GET_LOADNEXTPAGE ({state, commit}) {
        state.loading = true;
        return new Promise((resolve, reject) => {
            if (!state.movies.next_page_url) { reject(0) }
            axios.get(state.movies.next_page_url).then(res => {
                commit('MOVIES_COMMIT_APPEND_MOVIESDATA', res.data);
                state.loading = false;
                resolve();
            }).catch(() => {
                state.loading = false;
                reject();
            });
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
    MOVIES_ACTION_SAVE ({state, commit}, payload) {
        axios.post('/api/movie', payload).then(res => {
            state.$root.toast('Movie saved');
        }).catch(() => {
            state.$root.toast('Error while saving movie');
        });
    },
    MOVIES_ACTION_UPDATE ({state, commit}, {payload, id}) {
        axios.post('/api/movie/' + id + '/update', payload).then(res => {
            state.$root.toast('Movie updated');
        }).catch(() => {
            state.$root.toast('Error while updating movie');
        });
    },
    MOVIES_ACTION_RETRIEVE ({state, commit}, {payload, id}) {
        return new Promise((resolve, reject) => {
            axios.post('/api/movie/' + id + '/retrieve', payload).then(res => {
                state.$root.toast('Movie retrieved');
                resolve(res);
            }).catch(err => {
                state.$root.toast('Error while retrieving movie');
                reject(err);
            });
        });
    },
    MOVIES_ACTION_DELETE ({state, commit}, id) {
        axios.get('/api/movie/' + id + '/delete').then(() => {
            state.$root.toast('Movie deleted');
            state.$root.$router.go(-1);
        }).catch(() => {
            state.$root.toast('Error while deleting movie');
        });
    },
    MOVIES_ACTION_UPDATE_LAST_SEEN ({state, commit}, {id, date}) {
        return new Promise((resolve, reject) => {
            axios.get('/api/movie/' + id + '/lastSeen/' + date).then(res => {
                state.$root.toast('Updated last seen');
                resolve(res);
            }).catch(err => {
                state.$root.toast('Error while updating last seen');
                reject(err);
            });
        });
    },
    MOVIES_ACTION_BORROW ({state, commit}, {id, user}) {
        return new Promise((resolve, reject) => {
            axios.post('/api/movie/' + id + '/borrowTo/' + user.id).then(res => {
                state.$root.toast('Borrowed to: ' + user.name);
                resolve(res);
            }).catch(err => {
                state.$root.toast('Error while borrowing movie');
                reject(err);
            });
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
    },
    MOVIES_SET_ROOT (state, vueInstance) {
        state.$root = vueInstance;
    }
};

const getters = {
    MOVIES_GET_ALL: state => state.movies.data,
    MOVIES_GET_NEXT_PAGE_URL: state => state.movies.next_page_url,
    MOVIES_GET: state => state.movie,
    MOVIES_GET_LOADING: state => state.loading,
};

export default {
    state,
    actions,
    mutations,
    getters
};