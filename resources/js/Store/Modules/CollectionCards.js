const state = () => ({
    collections: [],
});

const getters = {
    collections: (state) => state.collections,
    collection: (state) => (id) => {
        return state.collections.find((collection) => collection.id === id);
    },
    collectionCards: (state, getters) => (id) => {
        return getters.collection(id).cards;
    },
    collectionCard: (state) => (id, cardId) => {
        const collection = state.collections.find(
            (collection) => collection.id === id
        );
        if (!collection.cards) {
            return null;
        }
        return collection.cards.find((card) => card.card_id === cardId);
    },
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
            .cards.find((card) => card.card_id === collectionCard.card_id).quantity =
            collectionCard.quantity;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
