<template>
    <data-grid
        :data="data"
        :fields="table.fields"
        :search-term="searchTerm"
        :filter="table.filter"
        :sort="table.sort"
    />
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import DataGrid from "@/Components/DataGrid";

export default {
    name: "CardIndex",

    components: { DataGrid },

    layout: Layout,

    title: "MTG Collector - Card Index",

    header: "Cards",

    data() {
        return {
            searchTerm: "",
            data: [
                {
                    id: 1,
                    card_name: "Force of Will",
                    card_id: 1,
                    set_name: "EMA",
                    set_id: 1,
                    features: ["Foil"],
                    quantity_collected: 3,
                    foil_collected: 1,
                    nonfoil_collected: 2,
                },
                {
                    id: 1,
                    card_name: "Counterspell",
                    card_id: 2,
                    set_name: "MP2",
                    set_id: 2,
                    features: ["Foil", "Masterpiece"],
                    quantity_collected: 1,
                    foil_collected: 1,
                    nonfoil_collected: 0,
                },
            ],
            table: {
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
                ],
            },
        };
    },
};
</script>
