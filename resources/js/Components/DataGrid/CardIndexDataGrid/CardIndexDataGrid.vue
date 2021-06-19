<template>
    <div>
        <CardSetSearch
            v-model="cardSearchTerm"
            v-model:setName="setSearchTerm"
        />
        <p v-if="searching" class="text-xs text-gray-400">Searching...</p>
        <data-table
            v-if="showData"
            v-model:sort="sortFields"
            class="mt-4"
            :data="data"
            :fields="fields"
        />
        <data-grid-pagination v-if="showPagination" :pagination="pagination" />
    </div>
</template>

<script>
import DataTable from "@/Components/DataGrid/DataGridTable";
import Search from "@/Components/Form/Search";
import DataGridPagination from "@/Components/DataGrid/DataGridPagination";
import CardSetSearch from "@/Components/Form/CardSearch/CardSetSearch";

export default {
    name: "CardIndexDataGrid",

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
        hideWithoutData: {
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
        forceShow: {
            type: Boolean,
            default: false,
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

    computed: {
        showData() {
            if (!this.hideWithoutData) {
                return true;
            }
            if (typeof this.data === "undefined") {
                return false;
            }
            if (!this.data) {
                return false;
            }
            return true;
        },
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
