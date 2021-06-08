<template>
    <div>
        <Search />
        <Table class="mt-4" :data="data" :fields="fields" v-model:sort="sort" />
    </div>
</template>

<script>
import Table from "@/Components/DataGridTable";
import Search from "@/Components/Form/Search";

export default {
    name: "DataGrid",

    components: { Table, Search },

    props: {
        searchTerm: {
            type: String,
            default: "",
        },
        data: {
            type: Array,
            default: () => {},
        },
        fields: {
            type: Array,
            default: () => {},
        },
        sort: {
            type: Object,
            default: () => {},
        },
        filter: {
            type: Array,
            default: () => {},
        },
    },

    data() {
        return {
            tableData: {},
        };
    },

    methods: {

    }
};
</script>

<style scoped></style>
