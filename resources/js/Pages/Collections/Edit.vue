<template>
    <div>
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Add Card to Collection
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4">
            <div class="md:col-span-2 lg:col-span-3">Name</div>
            <div>Set</div>
        </div>
    </div>
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import PrimaryButton from "@/Components/Buttons/PrimaryButton";

export default {
    name: "EditCollection",

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
