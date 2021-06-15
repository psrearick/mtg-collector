<template>
    <CardSetSearch v-model="cardSearchTerm" v-model:setName="setSearchTerm" />
    <p v-if="searching" class="text-sm text-green-600">Searching...</p>
    <CardSetSearchResults
        v-if="cards.data"
        v-model:cards="cards.data"
        v-model:sets="sets"
    />
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
                    this.cards = res.props.cards;
                    this.sets = res.props.sets;
                },
            });
        }, 1200),
        updateCardQuantity: function (change) {
            axios.post("/card-collections/card-collections", {
                change: change,
                collection: this.collection.id,
            });
        },
    },
};
</script>
