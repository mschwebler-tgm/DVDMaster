const state = {
    users: null
};

const actions = {
    USERS_ACTION_GET_All_EXCEPT_ME ({commit}) {
        return new Promise((resolve, reject) => {
            axios.get('/api/users/' + window.user_id).then(res => {
                commit('USERS_COMMIT_SET_ALL', res.data);
                resolve();
            }).catch(reject);
        });
    },
    USERS_ACTION_CREATE_USER ({commit}, name) {
        return new Promise((resolve, reject) => {
            axios.post('/api/users', {name}).then(res => {
                commit('USERS_COMMIT_PUSH_USER', res.data);
                resolve(res.data);
            }).catch(reject);
        });
    }
};

const mutations = {
    USERS_COMMIT_SET_ALL (state, users) {
        state.users = users;
    },
    USERS_COMMIT_PUSH_USER (state, user) {
        if (state.users) {
            state.users.push(user);
        }
    }
};

const getters = {
    USERS_GET_ALL: state => state.users,
};

export default {
    state,
    actions,
    mutations,
    getters
};