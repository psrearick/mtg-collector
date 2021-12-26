<template>
    <div><collection-search /></div>
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import CollectionSearch from "@/Pages/Dashboard/Partials/CollectionSearch";

export default {
    name: "Dashboard",

    components: { CollectionSearch },

    layout: Layout,

    title: "MTG Collector - Dashboard",

    header: "MTG Collector",
};
</script>
