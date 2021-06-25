<template>
    <CardSetSearch v-model="cardSearchTerm" v-model:setName="setSearchTerm" />
    <p v-if="searching" class="text-xs text-gray-400">Searching...</p>
    <CardSetSearchResults :pagination="search.cards" />
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
            this.runSearchResults(val);
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
        if (this.search && this.search.cards) {
            this.cardSearchTerm = this.search.cardQuery;
            this.setSearchTerm = this.search.setQuery;
            this.runSearchResults(this.search);
        }
    },

    methods: {
        runSearchResults(val) {
            let cardData = [];

            const cardResults =
                val &&
                Object.keys(val).length &&
                val.cards &&
                typeof val.cards.data !== "undefined";

            if (cardResults) {
                if (typeof val.cards.data.length === "undefined") {
                    cardData = Object.values(val.cards.data);
                } else if (val.cards.data.length) {
                    cardData = val.cards.data;
                }
            }

            this.$store.dispatch("addCardSearchResults", {
                searchResults: cardData,
            });

            let setData = [];

            const setResults =
                val &&
                Object.keys(val).length &&
                val.sets &&
                typeof val.sets.results !== "undefined";

            if (setResults) {
                if (typeof val.sets.results.length === "undefined") {
                    setData = Object.values(val.sets.results);
                } else if (val.sets.results.length) {
                    setData = val.sets.results;
                }
            }

            this.$store.dispatch("addSetSearchResults", {
                searchResults: setData,
            });
        },
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
