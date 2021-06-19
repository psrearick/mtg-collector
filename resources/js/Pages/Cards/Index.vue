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
            default: () => {},
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
            // this.table.data = _.map(this.cards.data, (card) => {
            //     return {
            //         id: card.id,
            //         card_name: card.name,
            //         card_id: card.id,
            //         set_name: card.set.name,
            //         set_id: card.set_id,
            //         price:
            //             card.hasNonFoil && card.price_normal
            //                 ? card.price_normal
            //                 : "",
            //         foil_price:
            //             card.hasFoil && card.price_foil ? card.price_foil : "",
            //         feature: card.feature,
            //         quantity_collected: 0,
            //         foil_collected: 0,
            //         nonfoil_collected: 0,
            //         edit_collection: "Edit",
            //     };
            // });

            // this.table.pagination = {
            //     current_page: this.cards.current_page,
            //     first_page_url: this.cards.first_page_url,
            //     last_page: this.cards.last_page,
            //     last_page_url: this.cards.last_page_url,
            //     next_page_url: this.cards.next_page_url,
            //     previous_page_url: this.cards.previous_page_url,
            //     links: this.cards.links,
            //     from: this.cards.from,
            //     to: this.cards.to,
            //     total: this.cards.total,
            // };

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
                "/cards/cards",
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
