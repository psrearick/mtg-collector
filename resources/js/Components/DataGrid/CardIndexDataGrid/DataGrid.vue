<template>
    <div>
        <!--        <Search v-if="showSearch" v-model="passThroughSearchTerm" />-->
        <CardSetSearch
            v-model="cardSearchTerm"
            v-model:setName="setSearchTerm"
        />
        <p v-if="searching" class="text-sm text-green-600">Searching...</p>
        <data-table
            v-if="data.length"
            v-model:sort="sortFields"
            class="mt-4"
            :data="data"
            :fields="fields"
        />
        <data-grid-pagination
            v-if="showPagination && data.length"
            :pagination="pagination"
        />
    </div>
</template>

<script>
import DataTable from "@/Components/DataGrid/DataGridTable";
import Search from "@/Components/Form/Search";
import DataGridPagination from "@/Components/DataGrid/DataGridPagination";
import CardSetSearch from "@/Components/DataGrid/CollectionEditDataGrid/CardSetSearch";

export default {
    name: "DataGrid",

    components: { CardSetSearch, DataTable, Search, DataGridPagination },

    props: {
        cardTerm: {
            type: String,
            default: "",
        },
        setTerm: {
            type: String,
            default: "",
        },
        searching: {
            type: Boolean,
            default: false,
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

    emits: ["update:setTerm", "update:cardTerm"],

    data() {
        return {
            tableData: {},
            sortFields: {},
            cardSearchTerm: "",
            setSearchTerm: "",
        };
    },

    watch: {
        cardSearchTerm(value) {
            this.$emit("update:cardTerm", value);
        },
        setSearchTerm(value) {
            this.$emit("update:setTerm", value);
        },
    },

    mounted() {
        this.sortFields = this.sort;
        this.cardSearchTerm = this.cardTerm;
        this.setSearchTerm = this.setTerm;
    },
};
</script>

<style scoped></style>
