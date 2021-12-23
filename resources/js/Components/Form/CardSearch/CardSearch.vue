<template>
    <card-set-search
        v-model="cardSearchTerm"
        v-model:set-name="setSearchTerm"
        :configure-table="false"
    />
    <p v-if="searching" class="text-xs text-gray-400">Searching...</p>
    <collection-card-search-results
        :paginator="paginator"
        :search="searchTerms"
        @update:paginator="updatePage"
    />
</template>

<script>
import CardSetSearch from "@/Components/Form/CardSearch/CardSetSearch";
import CardSetSearchResults from "@/Components/Form/CardSearch/CardSetSearchResults";
import CollectionCardSearchResults from "@/Components/DataGrid/CollectionCardSearchResults";
export default {
    name: "CardSearch",

    components: {
        CardSetSearchResults,
        CardSetSearch,
        CollectionCardSearchResults,
    },

    data() {
        return {
            cardSearchTerm: "",
            setSearchTerm: "",
            searching: false,
            default_paginator: {
                current_page: null,
                from: null,
                last_page: null,
                per_page: 15,
                to: null,
                total: null,
                links: [],
            },
            paginator: {
                current_page: null,
                from: null,
                last_page: null,
                per_page: 15,
                to: null,
                total: null,
                links: [],
            },
        };
    },

    computed: {
        searchTerms() {
            return {
                card: this.cardSearchTerm,
                set: this.setSearchTerm,
            };
        },
    },

    watch: {
        cardSearchTerm() {
            this.paginator = this.default_paginator;
            this.query();
        },
        setSearchTerm() {
            this.paginator = this.default_paginator;
            this.query();
        },
    },

    methods: {
        query: _.debounce(function () {
            this.searching = true;
            const collectionId = this.$store.getters.currentCollection.id;
            axios
                .post(
                    "/collections/collections/" + collectionId + "/edit/search",
                    {
                        card: this.cardSearchTerm,
                        set: this.setSearchTerm,
                        paginator: this.paginator,
                    }
                )
                .then((res) => {
                    this.searching = false;
                    this.runSearchResults(res.data.cards.data);
                    this.paginator = _.pick(res.data.cards, [
                        "current_page",
                        "from",
                        "last_page",
                        "per_page",
                        "to",
                        "total",
                        "links",
                    ]);
                });
        }, 1200),
        runSearchResults(val) {
            this.$store.dispatch("addCardSearchResults", {
                searchResults: val,
            });
        },
        updatePage(paginator) {
            this.paginator = paginator;
            this.query();
        },
    },
};
</script>
