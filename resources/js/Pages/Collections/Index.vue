<template>
    <div>
        <div v-if="!collections.length">
            <p>You do not have any collections. Please create one.</p>
            <inertia-link :href="route('collections.create')">
                <PrimaryButton class="mt-4" type="button"
                    >Create Collection</PrimaryButton
                >
            </inertia-link>
        </div>
        <div v-if="collections.length">
            <inertia-link :href="route('collections.create')">
                <PrimaryButton class="mb-4" type="button"
                    >Create Collection</PrimaryButton
                >
            </inertia-link>
            <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3">
                <inertia-link
                    v-for="(collection, index) in collections"
                    :key="index"
                    :href="
                        route('collections.show', { collection: collection.id })
                    "
                >
                    <CardListCard>{{ collection.name }}</CardListCard>
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
};
</script>
