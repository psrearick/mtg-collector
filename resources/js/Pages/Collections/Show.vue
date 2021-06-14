<template>
    <div>
        <CardList classes="md:grid-cols-4">
            <CardListCard>
                <dt class="text-sm font-medium text-gray-500 truncate">
                    Total Cards
                </dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">
                    0 Cards
                </dd>
            </CardListCard>
            <CardListCard>
                <dt class="text-sm font-medium text-gray-500 truncate">
                    Current Value
                </dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">$0.00</dd>
            </CardListCard>
            <CardListCard>
                <dt class="text-sm font-medium text-gray-500 truncate">
                    Acquired Value
                </dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">$0.00</dd>
            </CardListCard>
            <CardListCard>
                <dt class="text-sm font-medium text-gray-500 truncate">
                    Gain/Loss
                </dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">
                    $0.00 (0.00%)
                </dd>
            </CardListCard>
        </CardList>

        <div>Search</div>
        <div>Table</div>
    </div>
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import CardList from "@/Components/CardLists/CardList";
import CardListCard from "@/Components/CardLists/CardListCard";

export default {
    name: "Show",
    components: { CardListCard, CardList },
    layout: Layout,

    title: "MTG Collector - Collection",

    header: "",

    props: {
        collection: {
            type: Object,
            default: () => {},
        },
    },

    mounted() {
        this.$store.dispatch("updateHeader", { header: this.collection.name });
        this.$store.dispatch("updateSubheader", {
            subheader: this.collection.description,
        });
    },
};
</script>

<style scoped></style>
