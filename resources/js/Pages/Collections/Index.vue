<template>
    <div>
        <collections-show-card-list v-if="!isEmpty" :summary="totals" />
        <collection-folder-index
            :folders="folders"
            :collections="collections"
        />
    </div>
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import CollectionFolderIndex from "@/Shared/Components/CollectionFolderIndex";
import CollectionsShowCardList from "@/Components/CardLists/CollectionsShowCardList";

export default {
    name: "Index",

    components: { CollectionFolderIndex, CollectionsShowCardList },

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
        totals: {
            type: Object,
            default: () => {},
        },
    },

    computed: {
        isEmpty() {
            return this.folders.length === 0 && this.collections.length === 0;
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
