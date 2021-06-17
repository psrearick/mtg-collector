<template>
    <div>
        <div class="mb-12">
            <h3 class="text-lg leading-6 font-medium text-gray-900 py-4">
                Add Cards to Collection
            </h3>
            <div class="w-full">
                <CardSearch :collection="collection" />
            </div>
        </div>
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-8">
                Your Collection
            </h3>
            <div class="w-full">
                <CollectionEditDataGrid />
            </div>
        </div>
    </div>
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import PrimaryButton from "@/Components/Buttons/PrimaryButton";
import CardSearch from "@/Components/Form/CardSearch/CardSearch";
import CollectionEditDataGrid from "@/Components/DataGrid/CollectionDataGrid/CollectionEditDataGrid";

export default {
    name: "EditCollection",
    components: { CollectionEditDataGrid, CardSearch },
    layout: Layout,

    title: "MTG Collector - Edit Collection",

    props: {
        collection: {
            type: Object,
            default: () => {},
        },
    },

    mounted() {
        this.$store.dispatch("updateHeader", {
            header: "Edit: " + this.collection.name,
        });
        this.$store.dispatch("updateSubheader", {
            subheader: this.collection.description,
        });
        this.$store.dispatch("updateHeaderRightComponent", {
            component: {
                is: PrimaryButton,
                props: {
                    text: "Done Editing",
                    href: route("collections.show", {
                        collection: this.collection.id,
                    }),
                },
            },
        });
    },
};
</script>
