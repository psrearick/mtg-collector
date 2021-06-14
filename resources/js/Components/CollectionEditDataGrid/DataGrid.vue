<template>
    <CardSetSearch v-model="cardSearchTerm" v-model:setName="setSearchTerm" />
    <p v-if="searching" class="text-sm text-blue-600">Searching...</p>
    <CardSetSearchResults v-model:cards="cards" v-model:sets="sets" />
</template>

<script>
import CardSetSearch from "@/Components/CollectionEditDataGrid/CardSetSearch";
import CardSetSearchResults from "@/Components/CollectionEditDataGrid/CardSetSearchResults";
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
            console.log("searching");
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
