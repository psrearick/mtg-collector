<template>
    <CardSetSearch v-model="cardSearchTerm" v-model:setName="setSearchTerm" />
    <p v-if="searching" class="text-xs text-gray-400">Searching...</p>
    <CardSetSearchResults />
</template>

<script>
import CardSetSearch from "@/Components/Form/CardSearch/CardSetSearch";
import CardSetSearchResults from "@/Components/Form/CardSearch/CardSetSearchResults";
export default {
    name: "CardSearch",

    components: { CardSetSearchResults, CardSetSearch },

    props: {
        collection: {
            type: Object,
            default: () => {},
        },
        search: {
            type: Object,
            default: () => {},
        },
    },

    data() {
        return {
            cardSearchTerm: "",
            setSearchTerm: "",
            cards: [],
            sets: [],
            searching: false,
            // pagination: {},
        };
    },

    watch: {
        cardSearchTerm() {
            this.query();
        },
        setSearchTerm() {
            this.query();
        },
        search(val) {
            const cardData = [];

            const results =
                val &&
                Object.keys(val).length &&
                val.cards &&
                typeof val.cards.data !== "undefined" &&
                val.cards.data.length;

            if (results) {
                for (let card of val.cards.data) {
                    let foil = 0;
                    let nonFoil = 0;

                    const collectionFoil = this.findCardInStore({
                        card_id: card.id,
                        foil: 1,
                    });
                    if (collectionFoil) {
                        foil = collectionFoil.quantity;
                    }

                    const collectionNonFoil = this.findCardInStore({
                        card_id: card.id,
                        foil: 0,
                    });
                    if (collectionNonFoil) {
                        nonFoil = collectionNonFoil.quantity;
                    }

                    card.collectionQuantityFoil = foil;
                    card.collectionQuantityNonFoil = nonFoil;

                    cardData.push(card);
                }
            }

            let setData = [];

            const setResults =
                val &&
                Object.keys(val).length &&
                val.sets &&
                typeof val.sets.results !== "undefined" &&
                val.sets.results.length;

            if (setResults) {
                setData = val.sets.results;
            }

            this.$store.dispatch("addCardSearchResults", {
                searchResults: cardData,
            });
            this.$store.dispatch("addSetSearchResults", {
                searchResults: setData,
            });
        },
    },

    created() {
        this.emitter.on("updateCardQuantity", (change) => {
            this.updateCardQuantity(change);
        });
    },

    mounted() {
        this.addCollectionsToStore();
        let clear = { searchResults: [] };
        this.$store.dispatch("addCardSearchResults", clear);
        this.$store.dispatch("addSetSearchResults", clear);
    },

    methods: {
        query: _.debounce(function () {
            this.searching = true;
            this.cards = [];
            this.sets = [];
            this.$inertia.reload({
                data: {
                    card: this.cardSearchTerm,
                    set: this.setSearchTerm,
                },
                only: ["search"],
                onSuccess: () => {
                    this.searching = false;
                },
            });
        }, 1200),
        addCollectionsToStore: async function () {
            if (this.collection.cards) {
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
        getCardsWithQuantities: function () {},
        findCardInStore: function (card) {
            return this.$store.getters.collectionCard(
                this.collection.id,
                card.card_id,
                card.foil
            );
        },
        findCardInStoreOrCreate: async function (card) {
            const collectionCard = this.findCardInStore(card);
            if (collectionCard) {
                return collectionCard;
            }
            await this.createCard(card);
            return this.findCardInStore(card);
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
                    change: change,
                    collection: this.collection.id,
                })
                .then((res) => {
                    const data = res.data;
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
</script>
