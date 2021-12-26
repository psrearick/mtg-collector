<template>
    <div>
        <data-table
            v-if="shared.length > 0"
            :data="shared"
            :fields="table.fields"
            grid-name="shared-collections"
        />
        <p v-else>You have not saved any public collections.</p>
    </div>
</template>
<script>
import Layout from "@/Layouts/Authenticated";
import DataTable from "@/Components/DataGrid/DataGridTable";

export default {
    name: "Index",

    components: { DataTable },

    layout: Layout,

    title: "MTG Collector - Shared Collection",

    props: {
        shared: {
            type: Array,
            default: () => [],
        },
    },

    data() {
        return {
            table: {
                fields: [
                    {
                        visible: true,
                        type: "text",
                        link: true,
                        label: "Collection",
                        key: "name",
                        events: {
                            click: "shared_collection_click",
                        },
                    },
                    {
                        visible: true,
                        type: "text",
                        label: "User",
                        key: "user_name",
                    },
                    {
                        visible: true,
                        type: "text",
                        label: "Description",
                        key: "description",
                    },
                ],
            },
        };
    },

    mounted() {
        this.$store.dispatch("updateHeader", { header: "Shared Collections" });
    },

    created() {
        this.emitter.on("shared_collection_click", (collection) => {
            this.$inertia.get(`/shared/shared/${collection.id}`);
        });
    },
};
</script>
