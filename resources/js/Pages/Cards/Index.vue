<template>
    <data-grid />
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import DataGrid from "@/Components/DataGrid";

export default {
    name: "CardIndex",
    components: { DataGrid },
    layout: Layout,

    title: "MTG Collector - Card Index",

    header: "Cards",
};
</script>
