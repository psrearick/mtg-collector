<template>
    <div>
        <DataTable :fields="table.fields" :data="data" />
    </div>
</template>

<script>
import DataTable from "@/Components/DataGrid/DataGridTable";
export default {
    name: "CollectionShowDataGrid",

    components: { DataTable },

    props: {
        data: {
            type: Object,
            default: () => {},
        },
    },

    data() {
        return {
            table: {
                fields: [
                    {
                        visible: true,
                        type: "composite-text",
                        link: true,
                        label: "Card",
                        values: [
                            {
                                key: "name",
                                classes: "",
                            },
                            {
                                key: "foil_formatted",
                                classes: "text-sm text-gray-500 pl-2",
                            },
                        ],
                        events: {
                            click: "collection_card_name_click",
                        },
                    },
                    {
                        visible: true,
                        type: "text",
                        link: true,
                        label: "Set",
                        key: "set",
                        events: {
                            click: "collection_card_set_click",
                        },
                    },
                    {
                        visible: true,
                        type: "text",
                        label: "Features",
                        key: "features",
                    },
                    {
                        visible: true,
                        type: "currency",
                        label: "Today",
                        key: "today",
                    },
                    {
                        visible: true,
                        type: "text",
                        label: "Acquired Date",
                        key: "acquired_date",
                    },
                    {
                        visible: true,
                        type: "currency",
                        label: "Acquired Price",
                        key: "acquired_price",
                    },
                ],
            },
        };
    },
    created() {
        this.emitter.on("collection_card_name_click", () => {});
        this.emitter.on("collection_card_set_click", () => {});
    },
};
</script>

<style scoped></style>
