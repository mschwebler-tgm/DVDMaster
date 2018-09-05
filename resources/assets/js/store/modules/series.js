const state = {
    $root: null,
    series: {
        data: []
    },
    loading: false,
    singleSeries: {}
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
    SERIES_SET_ROOT (state, vueInstance) {
        state.$root = vueInstance;
    }
};

const getters = {
    SERIES_GET_ALL: state => state.series.data,
    SERIES_GET_NEXT_PAGE_URL: state => state.series.next_page_url,
    SERIES_GET: state => state.singleSeries,
};

export default {
    state,
    actions,
    mutations,
    getters
};