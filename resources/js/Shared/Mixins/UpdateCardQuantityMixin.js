export default {
    props: {
        collection: {
            type: Object,
            default: () => {},
        },
    },
    mounted() {
        this.addCollectionsToStore();
        let clear = { searchResults: [] };
        this.$store.dispatch("addCardSearchResults", clear);
        this.$store.dispatch("addSetSearchResults", clear);
    },
    created() {
        this.emitter.on("updateCardQuantity", (change) => {
            this.updateCardQuantity(change);
        });
    },
    methods: {
        addCollectionsToStore: async function () {
            if (this.collection.cards.length) {
                for (const card of this.collection.cards) {
                    await this.saveCard(card.pivot);
                }
            } else {
                await this.findCollectionInStoreOrCreate();
            }
        },
        createCard: function (card) {
            return this.$store.dispatch("addCardToCollection", {
                collectionCard: card,
            });
        },
        deleteCard: function (id, foil) {
            const collectionCard = this.$store.getters.collectionCard(
                this.collection.id,
                id,
                foil
            );
            if (collectionCard) {
                this.$store.dispatch("removeCardFromCollection", {
                    collectionCard: collectionCard,
                });
            }
        },
        findCardInStore: function (card) {
            return this.$store.getters.collectionCard(
                this.collection.id,
                card.card_id,
                card.foil
            );
        },
        findCollectionInStore: function () {
            return this.$store.getters.collection(this.collection.id);
        },
        findCollectionInStoreOrCreate: async function () {
            const storeCollection = this.findCollectionInStore();
            if (storeCollection) {
                return storeCollection;
            }
            await this.$store.dispatch("addCollection", {
                collection: {
                    id: this.collection.id,
                },
            });
            return this.findCollectionInStore();
        },
        saveCard: async function (collectionCard) {
            await this.findCollectionInStoreOrCreate();
            await this.updateCardInStore(collectionCard);
        },
        updateCardInStore: function (card) {
            const collectionCard = this.findCardInStore(card);
            if (collectionCard) {
                this.$store.dispatch("updateCardQuantityInCollection", {
                    collectionCard: card,
                });
            }
            this.createCard(card);
        },
        updateSearchResultsQuantity: function (data, change) {
            let id = change.id;
            let foil = change.foil;
            let quantity = 0;
            if (data.collectionCard) {
                quantity = data.collectionCard.quantity;
            }
            const card = this.$store.getters.cardSearchResultsCard(id);
            if (!card) {
                return;
            }
            this.$store.dispatch("updateCardSearchResultsCard", {
                id: id,
                foil: foil,
                quantity: quantity,
            });
        },
        updateCardQuantity: function (change) {
            axios
                .post("/card-collections/card-collections", {
                    ...change,
                    collection: this.collection.id,
                })
                .then((res) => {
                    const data = res.data;
                    if (data.error) {
                        return;
                    }

                    this.updateSearchResultsQuantity(data, change);
                    if (data.collectionCard) {
                        this.saveCard(data.collectionCard);
                    } else {
                        this.deleteCard(change.id, change.foil);
                    }
                    this.$inertia.reload({
                        only: ["collection", "collectionComplete"],
                    });
                });
        },
    },
};
