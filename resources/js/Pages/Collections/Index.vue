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
                    <CardListCard link>
                        <div class="py-4">
                            {{ collection.name }}
                        </div>
                        <div class="grid grid-cols-2 pb-4">
                            <div>
                                <p class="text-xs text-gray-500">Cards</p>
                                <p>75</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Value</p>
                                <p>$750.00</p>
                            </div>
                        </div>
                    </CardListCard>
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
