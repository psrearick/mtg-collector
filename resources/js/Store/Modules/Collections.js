const state = () => ({
    filteredCollection: {},
    currentCollection: {},
    shownRows: {},
    setCards: {},
});

const getters = {
    filteredCollection: (state) => state.filteredCollection,
    currentCollection: (state) => state.currentCollection,
    shownRows: (state) => state.shownRows,
    isShownRow: (state) => (row) => state.shownRows[row] === true,
    setCard: (state) => (card) => state.setCards[card] || null,
};

const actions = {
    addFilteredCollection({ commit }, collection) {
        commit("setFilteredCollection", collection);
    },
    updateCurrentCollection({ commit }, collection) {
        commit("updateCurrentCollection", collection);
    },
    toggleShownRow({ commit }, row_id) {
        commit("toggleShownRow", row_id);
    },
    unsetShownRows({ commit }) {
        commit("unsetShownRows");
    },
    addSetCard({ commit }, card) {
        commit("addSetCard", card);
    },
    unsetSetCards({ commit }) {
        commit("unsetSetCards");
    },
};

const mutations = {
    setFilteredCollection(state, { collection }) {
        state.filteredCollection = collection;
    },
    updateCurrentCollection(state, { collection }) {
        state.currentCollection = collection;
    },
    toggleShownRow(state, row_id) {
        state.shownRows[row_id] = !state.shownRows[row_id];
    },
    unsetShownRows(state) {
        state.shownRows = {};
    },
    addSetCard(state, card) {
        state.setCards[card.id] = card;
    },
    unsetSetCards(state) {
        state.setCards = {};
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
