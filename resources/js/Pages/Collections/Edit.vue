<template>
    <div>
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-8">
            Add Cards to Collection
        </h3>
        <div class="w-full">
            <DataGrid />
        </div>
    </div>
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import PrimaryButton from "@/Components/Buttons/PrimaryButton";
import DataGrid from "@/Components/DataGrid/CollectionEditDataGrid/DataGrid";

export default {
    name: "EditCollection",
    components: { DataGrid },
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
