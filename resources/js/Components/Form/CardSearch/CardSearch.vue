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

    mounted() {
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
    },
};
</script>
