const state = {
    stats: null
};

const actions = {
    STATS_ACTION_GET_ALL ({commit}) {
        return new Promise((resolve, reject) => {
            axios.get('/api/stats').then(res => {
                commit('STATS_COMMIT_SET_ALL', res.data);
                resolve();
            }).catch(reject);
        });
    }
};

const mutations = {
    STATS_COMMIT_SET_ALL (state, data) {
        state.stats = data;
    },
};

const getters = {
    STATS_GET_ALL: state => state.stats,
};

export default {
    state,
    actions,
    mutations,
    getters
};