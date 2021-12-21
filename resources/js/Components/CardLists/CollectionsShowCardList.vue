<template>
    <CardList classes="lg:grid-cols-4 mb-8">
        <CardListCard>
            <dt class="text-sm font-medium text-gray-500 truncate">
                Total Cards
            </dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900">
                {{ summary.totalCards }} Cards
            </dd>
        </CardListCard>
        <CardListCard>
            <dt class="text-sm font-medium text-gray-500 truncate">
                Current Value
            </dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900">
                {{ formattedCurrency(summary.currentValue) }}
            </dd>
        </CardListCard>
        <CardListCard>
            <dt class="text-sm font-medium text-gray-500 truncate">
                Acquired Value
            </dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900">
                {{ formattedCurrency(summary.acquiredValue) }}
            </dd>
        </CardListCard>
        <CardListCard>
            <dt class="text-sm font-medium text-gray-500 truncate">
                Gain/Loss
            </dt>
            <dd
                class="mt-1 text-3xl font-semibold"
                :class="
                    summary.gainLoss >= 0 ? 'text-gray-900' : 'text-red-500'
                "
            >
                {{ formattedCurrency(summary.gainLoss) }} ({{
                    formattedPercentage(summary.gainLossPercent)
                }})
            </dd>
        </CardListCard>
    </CardList>
</template>

<script>
import CardList from "@/Components/CardLists/CardList";
import CardListCard from "@/Components/CardLists/CardListCard";
import { formatCurrency, formatPercentage } from "@/Shared/api/ConvertValue";

export default {
    name: "CollectionsShowCardList",

    components: {
        CardList,
        CardListCard,
    },

    props: {
        summary: {
            type: Object,
            default: () => {},
        },
    },

    methods: {
        formattedCurrency(value) {
            return formatCurrency(value);
        },
        formattedPercentage(value) {
            return formatPercentage(value, 2, true, true);
        },
    },
};
</script>

<style scoped></style>
