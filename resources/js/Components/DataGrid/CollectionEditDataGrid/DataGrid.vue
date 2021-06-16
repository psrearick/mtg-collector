<template>
    <CardSetSearch v-model="cardSearchTerm" v-model:setName="setSearchTerm" />
    <p v-if="searching" class="text-xs text-gray-400">Searching...</p>
    <CardSetSearchResults />
</template>

<script>
import CardSetSearch from "@/Components/DataGrid/CollectionEditDataGrid/CardSetSearch";
import CardSetSearchResults from "@/Components/DataGrid/CollectionEditDataGrid/CardSetSearchResults";
import DataGridPagination from "@/Components/DataGrid/DataGridPagination";
export default {
    name: "DataGrid",

    components: { DataGridPagination, CardSetSearchResults, CardSetSearch },

    props: {
        collection: {
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
            pagination: {},
        };
    },

    watch: {
        cardSearchTerm() {
            this.search();
        },
        setSearchTerm() {
            this.search();
        },
        cards() {
            this.pagination = {
                current_page: this.cards.current_page,
                first_page_url: this.cards.first_page_url,
                last_page: this.cards.last_page,
                last_page_url: this.cards.last_page_url,
                next_page_url: this.cards.next_page_url,
                previous_page_url: this.cards.previous_page_url,
                links: this.cards.links,
                from: this.cards.from,
                to: this.cards.to,
                total: this.cards.total,
            };
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
        search: _.debounce(function () {
            this.searching = true;
            this.cards = [];
            this.sets = [];
            this.$inertia.reload({
                data: {
                    card: this.cardSearchTerm,
                    set: this.setSearchTerm,
                },
                onSuccess: (res) => {
                    this.searching = false;
                    this.$store.dispatch("addCardSearchResults", {
                        searchResults: this.getCardsWithQuantities(
                            res.props.cards
                        ),
                    });
                    this.$store.dispatch("addSetSearchResults", {
                        searchResults: res.props.sets,
                    });
                },
            });
        }, 1200),
        addCollectionsToStore: async function () {
            for (const card of this.collection.cards) {
                await this.saveCard(card.pivot);
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
        getCardsWithQuantities: function (cards) {
            if (!cards) {
                return cards;
            }

            const cardData = [];

            for (let card of cards.data) {
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

            cards.data = cardData;
            return cards;
        },
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
                        return this.saveCard(data.collectionCard);
                    }
                    this.deleteCard(change.id, change.foil);
                });
        },
    },
};
</script>
