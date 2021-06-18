<template>
    <div>
        <CardList classes="md:grid-cols-4 mb-8">
            <CardListCard>
                <dt class="text-sm font-medium text-gray-500 truncate">
                    Total Cards
                </dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">
                    {{ collection.summary.totalCards }} Cards
                </dd>
            </CardListCard>
            <CardListCard>
                <dt class="text-sm font-medium text-gray-500 truncate">
                    Current Value
                </dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">
                    {{ formattedCurrency(collection.summary.currentValue) }}
                </dd>
            </CardListCard>
            <CardListCard>
                <dt class="text-sm font-medium text-gray-500 truncate">
                    Acquired Value
                </dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">
                    {{ formattedCurrency(collection.summary.acquiredValue) }}
                </dd>
            </CardListCard>
            <CardListCard>
                <dt class="text-sm font-medium text-gray-500 truncate">
                    Gain/Loss
                </dt>
                <dd
                    class="mt-1 text-3xl font-semibold"
                    :class="
                        collection.summary.gainLoss > 0
                            ? 'text-gray-900'
                            : 'text-red-500'
                    "
                >
                    {{ formattedCurrency(collection.summary.gainLoss) }} ({{
                        formattedPercentage(collection.summary.gainLossPercent)
                    }})
                </dd>
            </CardListCard>
        </CardList>

        <div class="mb-8">Search</div>
        <div>
            <CollectionShowDataGrid :data="collection.cards" />
        </div>
    </div>
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import CardList from "@/Components/CardLists/CardList";
import CardListCard from "@/Components/CardLists/CardListCard";
import PrimaryButton from "@/Components/Buttons/PrimaryButton";
import CollectionShowDataGrid from "@/Components/DataGrid/CollectionDataGrid/CollectionShowDataGrid";
import { formatCurrency, formatPercentage } from "@/Shared/api/ConvertValue";

export default {
    name: "ShowCollection",

    components: { CardListCard, CardList, CollectionShowDataGrid },

    layout: Layout,

    title: "MTG Collector - Collection",

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
        this.$store.dispatch("updateHeaderRightComponent", {
            component: {
                is: PrimaryButton,
                props: {
                    text: "Edit Collection",
                    href: route("collections.edit", {
                        collection: this.collection.id,
                    }),
                },
            },
        });
    },

    methods: {
        formattedCurrency(value) {
            return formatCurrency(value);
        },
        formattedPercentage(value) {
            return formatPercentage(value, 2, true);
        },
    },
};
</script>

<style scoped></style>
