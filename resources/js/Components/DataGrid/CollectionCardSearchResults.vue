<template>
    <div>
        <div v-if="hasSearch">
            <ui-card v-if="!hasResults">
                <p>No cards found!</p>
            </ui-card>
            <ui-well v-if="hasResults" class="mt-4">
                <div
                    v-for="(card, index) in cards"
                    :key="index"
                    class="
                        md:flex
                        justify-between
                        bg-white
                        rounded-md
                        w-full
                        py-2
                        px-4
                        mb-2
                    "
                >
                    <div class="h-48 px-4 text-center">
                        <img
                            v-if="card.image.length"
                            :src="card.image"
                            :alt="card.name"
                            class="h-full inline"
                        />
                    </div>
                    <div class="flex flex-col justify-between">
                        <div class="text-center">
                            <p>
                                {{ card.name }} - {{ card.set_name }} ({{
                                    card.set_code
                                }})
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ card.features }}
                            </p>
                        </div>
                        <div class="flex justify-center py-4">
                            <vertical-incrementer
                                v-for="(finish, finishIndex) in card.finishes"
                                :key="finishIndex"
                                class="md:mx-4"
                                :label="finishIndex"
                                :model-value="card.quantities[finishIndex]"
                                :active="
                                    activeField === index &&
                                    activeFieldFinish === finishIndex
                                "
                                @activate="activate(index, finishIndex)"
                                @update:model-value="
                                    updateQuantity($event, card.id, finishIndex)
                                "
                            />
                        </div>
                    </div>
                    <div>
                        <p
                            v-for="(price, priceIndex) in card.today"
                            :key="priceIndex"
                            class="text-center md:text-right md:pr-1"
                        >
                            <span class="text-sm text-gray-500 mr-2">{{
                                card.finishes[priceIndex]
                            }}</span>
                            <span>{{ price ? format(price) : "N/A" }}</span>
                        </p>
                    </div>
                </div>
            </ui-well>
        </div>
        <data-grid-pagination-no-link
            v-if="hasResults"
            :pagination="pagination"
            @update:pagination="updatePage"
        />
    </div>
</template>

<script>
import { formatCurrency } from "@/Shared/api/ConvertValue";
import DataGridPaginationNoLink from "@/Components/DataGrid/DataGridPaginationNoLink";
import VerticalIncrementer from "@/Components/Buttons/VerticalIncrementer";
import UiCard from "@/UI/UICard";
import UiWell from "@/UI/UIWell";

export default {
    name: "CollectionCardSearchResults",

    components: {
        DataGridPaginationNoLink,
        UiCard,
        UiWell,
        VerticalIncrementer,
    },

    props: {
        paginator: {
            type: Object,
            default: () => {},
        },
        search: {
            type: Object,
            default: () => {},
        },
    },

    emits: ["update:paginator"],

    data() {
        return {
            activeField: null,
            activeFieldFinish: null,
        };
    },

    computed: {
        cards() {
            return this.$store.getters.cardSearchResults;
        },
        cardsLength() {
            if (!this.cards) {
                return 0;
            }

            return Object.keys(this.cards).length;
        },
        hasResults() {
            return this.cardsLength > 0;
        },
        hasSearch() {
            return this.search.card.length > 0 || this.search.set.length > 0;
        },
        pagination() {
            return this.paginator ? this.paginator : {};
        },
    },

    methods: {
        activate(id, finish) {
            this.activeField = id;
            this.activeFieldFinish = finish;
        },
        format(value) {
            return value ? formatCurrency(value) : "N/A";
        },
        updatePage(page) {
            this.$emit("update:paginator", page);
        },
        updateQuantity: function (value, id, finish) {
            this.activeField = null;
            this.activeFieldFinish = null;
            let change =
                value -
                (this.cards.find((card) => card.id === id)["quantities"][
                    finish
                ] || 0);
            if (change === 0) {
                return;
            }

            this.emitter.emit("updateCardQuantity", {
                change: change,
                id: id,
                finish: finish,
            });
        },
    },
};
</script>
