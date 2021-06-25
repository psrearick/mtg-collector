<template>
    <div>
        <Search v-if="showSearch" v-model="passThroughSearchTerm" />
        <p v-if="searching" class="text-xs text-gray-400">Searching...</p>
        <data-table
            v-model:sort="sortFields"
            class="mt-4"
            :data="data"
            :fields="fields"
            :classes="classes"
        />
        <data-grid-pagination v-if="showPagination" :pagination="pagination" />
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
        classes: {
            type: Object,
            default: () => {},
        },
        searchTerm: {
            type: String,
            default: "",
        },
        showSearch: {
            type: Boolean,
            default: true,
        },
        showPagination: {
            type: Boolean,
            default: true,
        },
        data: {
            type: Array,
            default: () => {},
        },
        fields: {
            type: Array,
            default: () => {},
        },
        searching: {
            type: Boolean,
            default: false,
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

    emits: ["update:searchTerm"],

    data() {
        return {
            tableData: {},
            sortFields: {},
            passThroughSearchTerm: "",
        };
    },

    watch: {
        passThroughSearchTerm(value) {
            this.$emit("update:searchTerm", value);
        },
    },

    mounted() {
        this.sortFields = this.sort;
        this.passThroughSearchTerm = this.searchTerm;
    },
};
</script>

<style scoped></style>
