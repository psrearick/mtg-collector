<template>
    <div>
        <Search />
        <data-table
            v-model:sort="sortFields"
            class="mt-4"
            :data="data"
            :fields="fields"
        />
        <data-grid-pagination :pagination="pagination" />
    </div>
</template>

<script>
import DataTable from "@/Components/DataGrid/DataGridTable";
import Search from "@/Components/Form/Search";
import DataGridPagination from "@/Components/DataGrid/DataGridPagination";

export default {
    name: "DataGrid",

    components: { DataTable, Search, DataGridPagination },

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
        pagination: {
            type: Object,
            default: () => {},
        },
    },

    data() {
        return {
            tableData: {},
            sortFields: {},
        };
    },

    mounted() {
        this.sortFields = this.sort;
    },

    methods: {},
};
</script>

<style scoped></style>
