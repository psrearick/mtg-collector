const state = () => ({
    filteredCollection: {},
});

const getters = {
    filteredCollection: (state) => state.filteredCollection,
};

const actions = {
    addFilteredCollection({ commit }, collection) {
        commit("setFilteredCollection", collection);
    },
};

const mutations = {
    setFilteredCollection(state, { collection }) {
        state.filteredCollection = collection;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
