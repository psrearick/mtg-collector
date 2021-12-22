export default {
    props: {
        page: {
            type: Object,
            default: () => {},
        },
    },
    mounted() {
        // this.addCollectionsToStore();
        let clear = { searchResults: [] };
        this.$store.dispatch("addCardSearchResults", clear);
        this.$store.dispatch("addSetSearchResults", clear);
    },
    created() {
        const emitters = this.$store.getters.emitters;
        if (emitters.indexOf("updateCardQuantity") === -1) {
            this.$store.dispatch("addEmitter", "updateCardQuantity");
            this.emitter.on("updateCardQuantity", (change) => {
                this.updateCardQuantity(change);
            });
        }
    },
    methods: {
        // addCollectionsToStore: async function () {
        //     if (this.page.cards.length) {
        //         for (const card of this.page.cards) {
        //             await this.saveCard(card.pivot);
        //         }
        //     } else {
        //         await this.findCollectionInStoreOrCreate();
        //     }
        // },
        // createCard: function (card) {
        //     return this.$store.dispatch("addCardToCollection", {
        //         collectionCard: card,
        //     });
        // },
        // deleteCard: function (id, finish) {
        //     const collectionCard = this.$store.getters.collectionCard(
        //         this.page.collection.id,
        //         id,
        //         finish
        //     );
        //     if (collectionCard) {
        //         this.$store.dispatch("removeCardFromCollection", {
        //             collectionCard: collectionCard,
        //         });
        //     }
        // },
        // findCardInStore: function (card) {
        //     return this.$store.getters.collectionCard(
        //         this.page.collection.id,
        //         card.card_id,
        //         card.finish
        //     );
        // },
        // findCollectionInStore: function () {
        //     return this.$store.getters.collection(this.page.collection.id);
        // },
        // findCollectionInStoreOrCreate: async function () {
        //     const storeCollection = this.findCollectionInStore();
        //     if (storeCollection) {
        //         return storeCollection;
        //     }
        //     await this.$store.dispatch("addCollection", {
        //         collection: {
        //             id: this.page.collection.id,
        //         },
        //     });
        //     return this.findCollectionInStore();
        // },
        // saveCard: async function (collectionCard) {
        //     await this.findCollectionInStoreOrCreate();
        //     await this.updateCardInStore(collectionCard);
        // },
        // updateCardInStore: function (card) {
        //     const collectionCard = this.findCardInStore(card);
        //     if (collectionCard) {
        //         this.$store.dispatch("updateCardQuantityInCollection", {
        //             collectionCard: card,
        //         });
        //     }
        //     this.createCard(card);
        // },
        // updateSearchResultsQuantity: function (data, change) {
        //     let id = change.id;
        //     let finish = change.finish;
        //     let quantity = 0;
        //     if (data.collectionCard) {
        //         quantity = data.collectionCard.quantity;
        //     }
        //     const card = this.$store.getters.cardSearchResultsCard(id);
        //     if (!card) {
        //         return;
        //     }
        //     this.$store.dispatch("updateCardSearchResultsCard", {
        //         id: id,
        //         finish: finish,
        //         quantity: quantity,
        //     });
        // },
        updateCardQuantity: function (change) {
            let collection =
                this.$store.getters.currentCollection || this.page.collection;

            axios
                .post("/card-collections/card-collections", {
                    ...change,
                    collection: collection.id,
                })
                .then((res) => {
                    const data = res.data;
                    if (data.error) {
                        return;
                    }

                    this.$store.dispatch(
                        "updateCardSearchResultsCard",
                        data.searchCard
                    );

                    this.$inertia.reload({
                        only: ["page"],
                    });
                });
        },
    },
};
