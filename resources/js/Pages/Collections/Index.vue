<template>
    <div>
        <collection-folder-index
            :folders="folders"
            :collections="collections"
        />
    </div>
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import CollectionFolderIndex from "@/Shared/Components/CollectionFolderIndex";

export default {
    name: "Index",

    components: { CollectionFolderIndex },

    layout: Layout,

    title: "MTG Collector - Collection Index",

    header: "Collections",

    props: {
        collections: {
            type: Array,
            default: () => [],
        },
        folders: {
            type: Array,
            default: () => [],
        },
    },

    mounted() {
        this.$store.dispatch("updateHeaderRightComponent", {
            component: {
                is: "CollectionIndexHeaderRight",
            },
        });
    },
};
</script>
