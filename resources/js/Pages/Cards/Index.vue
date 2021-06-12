<template>
    <data-grid
        v-model:search-term="searchTerm"
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
import DataGrid from "@/Components/DataGrid/DataGrid";

export default {
    name: "CardIndex",

    components: { DataGrid },

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
        cardCount: {
            type: Number,
            default: 0,
        },
    },

    title: "MTG Collector - Card Index",

    header: "Cards",

    data() {
        return {
            searchTerm: "",
            table: {
                data: [],
                filter: [],
                sort: {},
                fields: [
                    {
                        visible: true,
                        type: "text",
                        link: true,
                        label: "Card",
                        key: "card_name",
                        event: "card_name_click",
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
                        type: "array",
                        link: false,
                        label: "Features",
                        key: "features",
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
                pagination: {
                    current_page: this.cards.current_page,
                    first_page_url: this.cards.first_page_url,
                    last_page: this.cards.last_page,
                    last_page_url: this.cards.last_page_url,
                    next_page_url: this.cards.next_page_url,
                    previous_page_url: this.cards.previous_page_url,
                    links: this.cards.links,
                    pages: 10,
                    per_page: this.perPage,
                    card_count: this.cardCount,
                    card_count_on_page: this.cards.data.length,
                },
            },
        };
    },
    mounted() {
        this.table.data = this.cards.data.map((card) => {
            return {
                id: card.id,
                card_name: card.name,
                card_id: card.id,
                set_name: card.set.name,
                set_id: card.set_id,
                price: card.price_normal,
                foil_price: card.price_foil,
                features: [],
                quantity_collected: 0,
                foil_collected: 0,
                nonfoil_collected: 0,
                edit_collection: "Edit",
            };
        });
    },
};
</script>
