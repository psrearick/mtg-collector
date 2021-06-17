<template>
    <CardIndexDataGrid
        v-model:card-term="cardSearchTerm"
        v-model:set-term="setSearchTerm"
        v-model:searching="searching"
        :data="table.data"
        :fields="table.fields"
        :show-search="true"
        :show-pagination="true"
        :filter="table.filter"
        :sort="table.sort"
        :pagination="table.pagination"
    />
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import CardIndexDataGrid from "@/Components/DataGrid/CardIndexDataGrid/CardIndexDataGrid";

export default {
    name: "CardIndex",

    components: { CardIndexDataGrid },

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
            table: {
                data: [],
                filter: [],
                sort: {},
                fields: [
                    {
                        visible: true,
                        type: "text",
                        link: true,
                        hover: true,
                        label: "Card",
                        key: "card_name",
                        events: {
                            click: "card_name_click",
                            hover: "card_name_hover",
                        },
                        sortable: true,
                        filterable: false,
                    },
                    {
                        visible: true,
                        type: "text",
                        link: true,
                        label: "Set",
                        key: "set_name",
                        event: "set_name_click",
                        sortable: true,
                        filterable: true,
                    },
                    {
                        visible: true,
                        type: "text",
                        link: false,
                        label: "Features",
                        key: "feature",
                        sortable: false,
                        filterable: true,
                    },
                    {
                        visible: true,
                        type: "currency",
                        link: false,
                        label: "Non-Foil Price",
                        key: "price",
                        sortable: true,
                        filterable: true,
                    },
                    {
                        visible: true,
                        type: "currency",
                        link: false,
                        label: "Foil Price",
                        key: "foil_price",
                        sortable: true,
                        filterable: true,
                    },
                    {
                        visible: true,
                        type: "text",
                        link: true,
                        label: "Collected",
                        key: "quantity_collected",
                        sortable: true,
                        filterable: true,
                        event: "collected_clicked",
                    },
                    {
                        visible: false,
                        type: "text",
                        link: false,
                        label: "Non-Foil Collected",
                        key: "nonfoil_collected",
                        sortable: false,
                        filterable: true,
                    },
                    {
                        visible: false,
                        type: "text",
                        link: false,
                        label: "Foil Collected",
                        key: "foil_collected",
                        sortable: false,
                        filterable: true,
                    },
                    {
                        visible: true,
                        type: "text",
                        link: true,
                        label: "Edit Collection",
                        key: "edit_collection",
                        sortable: false,
                        filterable: false,
                    },
                ],
                pagination: {},
            },
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
        // this.cardSearchTerm = this.cardQuery;
        // this.setSearchTerm = this.setQuery;
        this.mount();
        this.emitter.on("card_name_click", (card) => {
            this.showCard(card.id);
        });
    },

    methods: {
        mount() {
            this.table.data = _.map(this.cards.data, (card) => {
                return {
                    id: card.id,
                    card_name: card.name,
                    card_id: card.id,
                    set_name: card.set.name,
                    set_id: card.set_id,
                    price: card.hasNonFoil ? card.price_normal : "",
                    foil_price: card.hasFoil ? card.price_foil : "",
                    feature: card.feature,
                    quantity_collected: 0,
                    foil_collected: 0,
                    nonfoil_collected: 0,
                    edit_collection: "Edit",
                };
            });

            this.table.pagination = {
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

            this.cardSearchTerm = this.cardQuery;
            this.setSearchTerm = this.setQuery;
        },
        showCard(id) {
            this.$inertia.get(`/cards/cards/${id}`);
        },
        search: _.debounce(function () {
            this.table.data = [];
            if (!this.cardSearchTerm && !this.setSearchTerm) {
                return;
            }
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
