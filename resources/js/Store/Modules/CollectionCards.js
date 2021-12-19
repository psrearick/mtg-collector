const state = () => ({
    collections: [],
    cardSearchResults: [],
    setSearchResults: [],
});

const getters = {
    collections: (state) => state.collections,
    collection: (state) => (id) => {
        return state.collections.find((collection) => collection.id === id);
    },
    collectionCards: (state, getters) => (id) => {
        return getters.collection(id).cards;
    },
    collectionCard: (state) => (id, cardId, finish) => {
        const collection = state.collections.find(
            (collection) => collection.id === id
        );
        if (!collection.cards) {
            return null;
        }
        return collection.cards.find(
            (card) => card.card_id === cardId && card.finish === finish
        );
    },
    cardSearchResults: (state) => state.cardSearchResults,
    cardSearchResultsCard: (state) => (id) => {
        return state.cardSearchResults.find((card) => card.id === id);
    },
    setSearchResults: (state) => state.setSearchResults,
};

const actions = {
    addCollection({ commit }, collection) {
        commit("setCollection", collection);
    },
    removeCollection({ commit }, collectionId) {
        commit("unsetCollection", collectionId);
    },
    addCardToCollection({ commit }, collectionCard) {
        commit("setCollectionCard", collectionCard);
    },
    removeCardFromCollection({ commit }, collectionCard) {
        commit("unsetCollectionCard", collectionCard);
    },
    updateCardQuantityInCollection({ commit }, collectionCard) {
        commit("updateCollectionCard", collectionCard);
    },
    addCardSearchResults({ commit }, searchResults) {
        commit("setCardSearchResults", searchResults);
    },
    updateCardSearchResultsCard({ commit }, card) {
        commit("updateCardSearchResultsCard", card);
    },
    addSetSearchResults({ commit }, searchResults) {
        commit("setSetSearchResults", searchResults);
    },
};

const mutations = {
    setCollection(state, { collection }) {
        if (!collection.cards) {
            collection.cards = [];
        }
        state.collections.push(collection);
    },
    unsetCollection(state, { collectionId }) {
        const index = state.collections.findIndex(
            (collection) => collection.id === collectionId
        );
        state.collections.splice(index, 1);
    },
    setCollectionCard(state, { collectionCard }) {
        state.collections
            .find(
                (collection) => collection.id === collectionCard.collection_id
            )
            .cards.push(collectionCard);
    },
    unsetCollectionCard(state, { collectionCard }) {
        const collection = state.collections.find(
            (collection) => collection.id === collectionCard.collection_id
        );
        const index = collection.cards.findIndex(
            (card) => card.card_id === collectionCard.card_id
        );
        collection.cards.splice(index, 1);
    },
    updateCollectionCard(state, { collectionCard }) {
        state.collections
            .find(
                (collection) => collection.id === collectionCard.collection_id
            )
            .cards.find(
                (card) => card.card_id === collectionCard.card_id
            ).quantity = collectionCard.quantity;
    },
    setCardSearchResults(state, { searchResults }) {
        state.cardSearchResults = searchResults;
    },
    updateCardSearchResultsCard(state, card) {
        const srCardId = state.cardSearchResults.findIndex(
            (srCard) => srCard.id === card.id
        );
        state.cardSearchResults[srCardId] = card;
    },
    setSetSearchResults(state, { searchResults }) {
        state.setSearchResults = searchResults;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
