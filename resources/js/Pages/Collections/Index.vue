<template>
    <div>
        <div v-if="!collections.length">
            <p>You do not have any collections. Please create one.</p>
        </div>
        <div v-if="collections.length">
            <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3">
                <inertia-link
                    v-for="(collection, index) in collections"
                    :key="index"
                    :href="
                        route('collections.show', { collection: collection.id })
                    "
                >
                    <CardListCard link>{{ collection.name }}</CardListCard>
                </inertia-link>
            </div>
        </div>
    </div>
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import PrimaryButton from "@/Components/Buttons/PrimaryButton";
import CardListCard from "@/Components/CardLists/CardListCard";
export default {
    name: "Index",

    components: { CardListCard, PrimaryButton },

    layout: Layout,

    title: "MTG Collector - Collection Index",

    header: "Collections",

    props: {
        collections: {
            type: Array,
            default: () => {},
        },
    },

    mounted() {
        this.$store.dispatch("updateHeaderRightComponent", {
            component: {
                is: PrimaryButton,
                props: {
                    text: "Create Collection",
                    href: route("collections.create"),
                },
            },
        });
    },
};
</script>
