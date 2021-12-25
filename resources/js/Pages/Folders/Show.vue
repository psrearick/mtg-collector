<template>
    <div>
        <collections-show-card-list v-if="!isEmpty" :summary="totals" />
        <collection-folder-index
            v-if="!isEmpty"
            :folders="folders"
            :collections="collections"
        />
        <p v-else>This folder is empty.</p>
    </div>
</template>
<script>
import Layout from "@/Layouts/Authenticated";
import CollectionFolderIndex from "@/Shared/Components/CollectionFolderIndex";
import CollectionsShowCardList from "@/Components/CardLists/CollectionsShowCardList";

export default {
    name: "Show",

    components: { CollectionFolderIndex, CollectionsShowCardList },

    layout: Layout,

    title: "MTG Collector - Folder",

    props: {
        folder: {
            type: Object,
            default: () => {},
        },
        folders: {
            type: Array,
            default: () => [],
        },
        collections: {
            type: Array,
            default: () => [],
        },
        totals: {
            type: Array,
            default: () => {},
        },
    },

    computed: {
        isEmpty() {
            return this.folders.length === 0 && this.collections.length === 0;
        },
    },

    mounted() {
        this.$store.dispatch("updateHeader", { header: this.folder.name });
        this.$store.dispatch("updateSubheader", {
            subheader: this.folder.description,
        });
        this.$store.dispatch("updateHeaderRightComponent", {
            component: {
                is: "CollectionIndexHeaderRight",
                props: {
                    folder: this.folder.id,
                },
            },
        });
    },
};
</script>
