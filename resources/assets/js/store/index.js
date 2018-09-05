import Vuex from 'vuex';
import Vue from 'vue';

import movies from './modules/movies';
import series from './modules/series';
import users from './modules/users';
import stats from './modules/stats';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        movies,
        series,
        users,
        stats
    }
});
