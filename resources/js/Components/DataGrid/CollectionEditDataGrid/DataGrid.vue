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
export default {
    name: "DataGrid",

    components: { CardSetSearchResults, CardSetSearch },

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
            this.search();
        },
        setSearchTerm() {
            this.search();
        },
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
    },
};
</script>
