<template>
    <div>
        <DataTable :fields="table.fields" :data="data" />
    </div>
</template>

<script>
import DataTable from "@/Components/DataGrid/DataGridTable";
export default {
    name: "CollectionEditDataGrid",
    components: { DataTable },
    data() {
        return {
            table: {
                fields: [
                    {
                        visible: true,
                        type: "text",
                        link: true,
                        label: "Card",
                        key: "name",
                        events: {
                            click: "collection_card_name_click",
                        },
                    },
                    {
                        visible: true,
                        type: "text",
                        link: true,
                        label: "Set",
                        key: "set_name",
                        events: {
                            click: "collection_card_set_click",
                        },
                    },
                    {
                        visible: true,
                        type: "text",
                        label: "Finish",
                        key: "finish",
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
    methods: {
        data() {},
    },
};
</script>

<style scoped></style>
