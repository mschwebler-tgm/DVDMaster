const state = {
    $root: null,
    series: {
        data: []
    },
    singleSeries: {},
    filter: {},
    loading: false,
    searching: false
};

const actions = {
    SERIES_ACTION_GET_FIRSTPAGE ({state, commit}) {
        state.loading = true;
        return new Promise((resolve, reject) => {
            axios.get('/api/series').then(res => {
                commit('SERIES_COMMIT_SET_SERIESDATA', res.data);
                state.loading = false;
                resolve();
            }).catch(() => {
                state.loading = false;
                reject();
            });
        });
    },
    SERIES_ACTION_GET_LOADNEXTPAGE ({state, commit}) {
        state.loading = true;
        return new Promise((resolve, reject) => {
            if (!state.series.next_page_url) { reject(0) }
            let nextPageUrl = decodeURIComponent(state.series.next_page_url);
            axios.get(nextPageUrl).then(res => {
                commit('SERIES_COMMIT_APPEND_SERIESDATA', res.data);
                state.loading = false;
                resolve();
            }).catch(() => {
                state.loading = false;
                reject();
            });
        });
    },
    SERIES_ACTION_SEARCH ({commit, state}) {
        state.searching = true;
        function _pluckNames(dataArray) {
            let names = [];
            for (let data of dataArray) {
                names.push(data.name);
            }
            return names;
        }
        let filter = _.clone(state.filter);
        filter.genres && (filter.genres = _pluckNames(filter.genres));
        filter.actors && (filter.actors = _pluckNames(filter.actors));
        return new Promise((resolve, reject) => {
            axios.get('/api/customSearch/series', {params: filter}).then(res => {
                commit('SERIES_COMMIT_SET_SERIESDATA', res.data);
                state.searching = false;
                resolve();
            }).catch(() => {
                state.searching = false;
                reject();
            });
        });
    },
    SERIES_ACTION_SAVE ({state, commit}, payload) {
        return new Promise((resolve, reject) => {
            axios.post('/api/series', payload).then(res => {
                state.$root.toast('Series saved');
                resolve()
            }).catch(() => {
                state.$root.toast('Error while saving series');
                reject();
            });
        });
    },
};

const mutations = {
    SERIES_COMMIT_SET_SERIESDATA (state, data) {
        state.series = data;
    },
    SERIES_COMMIT_APPEND_SERIESDATA (state, data) {
        data.data = state.series.data.concat(data.data);
        state.series = data;
    },
    SERIES_SET_ROOT (state, vueInstance) {
        state.$root = vueInstance;
    },
    SERIES_COMMIT_FILTER_UPDATE (state, {type, data}) {
        Vue.set(state.filter, type, data);
    }
};

const getters = {
    SERIES_GET_ALL: state => state.series.data,
    SERIES_GET_NEXT_PAGE_URL: state => state.series.next_page_url,
    SERIES_GET: state => state.singleSeries,
    SERIES_GET_FILTER: state => state.filter,
    SERIES_GET_LOADING: state => state.loading,
    SERIES_GET_SEARCHING: state => state.searching,
};

export default {
    state,
    actions,
    mutations,
    getters
};