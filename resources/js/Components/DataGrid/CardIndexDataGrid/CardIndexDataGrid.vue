<template>
    <div>
        <card-set-search
            v-if="showSearch"
            v-model="cardSearchTerm"
            v-model:set-name="setSearchTerm"
            :card-search="cardSearch"
            :set-search="setSearch"
            :configure-table="configureTable"
            @gridConfigurationClick="
                gridConfigurationPanelShow = !gridConfigurationPanelShow
            "
        />
        <p v-if="searching" class="text-xs text-gray-400">Searching...</p>
        <data-table
            v-if="showData"
            class="mt-4"
            :data="data"
            :fields="fields"
            :select-menu="selectMenu"
            :field-rows="fieldRows"
            :grid-name="gridName"
        />
        <data-grid-pagination v-if="showPagination" :pagination="pagination" />
        <grid-configuration-panel
            v-model:show="gridConfigurationPanelShow"
            :fields="fields"
            :grid-name="gridName"
        />
    </div>
</template>

<script>
import DataTable from "@/Components/DataGrid/DataGridTable";
import Search from "@/Components/Form/Search";
import DataGridPagination from "@/Components/DataGrid/DataGridPagination";
import CardSetSearch from "@/Components/Form/CardSearch/CardSetSearch";
import GridConfigurationPanel from "@/Components/Panels/GridConfigurationPanel";

export default {
    name: "CardIndexDataGrid",

    components: {
        CardSetSearch,
        DataTable,
        Search,
        DataGridPagination,
        GridConfigurationPanel,
    },

    props: {
        gridName: {
            type: String,
            default: "",
        },
        cardSearch: {
            type: Boolean,
            default: true,
        },
        cardTerm: {
            type: String,
            default: "",
        },
        setSearch: {
            type: Boolean,
            default: true,
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
            default: () => [],
        },
        configureTable: {
            type: Boolean,
            default: true,
        },
        fields: {
            type: Array,
            default: () => [],
        },
        selectMenu: {
            type: Array,
            default: () => [],
        },
        fieldRows: {
            type: Array,
            default: () => [],
        },
        hideWithoutData: {
            type: Boolean,
            default: false,
        },
        filter: {
            type: Array,
            default: () => [],
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
            cardSearchTerm: "",
            setSearchTerm: "",
            gridConfigurationPanelShow: false,
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
        this.cardSearchTerm = this.cardTerm;
        this.setSearchTerm = this.setTerm;
    },
};
</script>
