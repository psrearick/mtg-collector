<template>
    <div>
        <Search />
        <Table
            v-model:sort="sortFields"
            class="mt-4"
            :data="data"
            :fields="fields"
        />
    </div>
</template>

<script>
import Table from "@/Components/DataGrid/DataGridTable";
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
