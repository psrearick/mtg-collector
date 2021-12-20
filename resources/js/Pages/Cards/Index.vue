<template>
    <CardIndexDataGrid
        v-model:card-term="cardSearchTerm"
        v-model:set-term="setSearchTerm"
        v-model:searching="searching"
        :data="cards.data"
        :fields="table.fields"
        :show-search="true"
        :show-pagination="true"
        :filter="table.filter"
        :sort="table.sort"
        :pagination="cards"
        :hide-without-data="true"
    />
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import CardIndexDataGrid from "@/Components/DataGrid/CardIndexDataGrid/CardIndexDataGrid";
import CardsIndexTable from "@/Shared/TableDefinitions/CardsIndexTable";

export default {
    name: "CardIndex",

    components: { CardIndexDataGrid },

    mixins: [CardsIndexTable],

    layout: Layout,

    props: {
        cards: {
            type: Object,
            default: () => ({ cards: [] }),
        },
        perPage: {
            type: Number,
            default: 0,
        },
        cardQuery: {
            type: String,
            default: "",
        },
        setQuery: {
            type: String,
            default: "",
        },
    },

    title: "MTG Collector - Card Index",

    header: "Cards",

    data() {
        return {
            setSearchTerm: "",
            cardSearchTerm: "",
            searching: false,
        };
    },

    watch: {
        cardSearchTerm() {
            if (this.cardSearchTerm !== this.cardQuery) {
                this.search();
            }
        },
        setSearchTerm() {
            if (this.setSearchTerm !== this.setQuery) {
                this.search();
            }
        },
    },

    created() {
        this.mount();
        this.emitter.on("card_name_click", (card) => {
            this.showCard(card.id);
        });
    },

    methods: {
        mount() {
            this.table.data = this.cards.cards;
            this.cardSearchTerm = this.cardQuery;
            this.setSearchTerm = this.setQuery;
        },
        showCard(id) {
            this.$inertia.get(`/cards/cards/${id}`);
        },
        search: _.debounce(function () {
            this.table.data = [];
            this.searching = true;
            this.$inertia.get(
                "/cards/search",
                {
                    card: this.cardSearchTerm,
                    set: this.setSearchTerm,
                },
                {
                    onSuccess: () => {
                        this.searching = false;
                        this.mount();
                    },
                }
            );
        }, 1200),
    },
};
</script>
