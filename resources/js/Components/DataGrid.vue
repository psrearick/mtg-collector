<template>
    <div>
        <Search />
        <Table class="mt-4" />
    </div>
</template>

<script>
import Table from "@/Components/Table";
import Search from "@/Components/Form/Search";

export default {
    name: "DataGrid",

    components: { Table, Search },
};
</script>

<style scoped></style>
