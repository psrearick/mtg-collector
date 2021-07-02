<template>
    <div class="w-full">
        <div
            v-if="cards.length || sets.length"
            class="rounded-md mt-4 p-4 bg-gray-200"
        >
            <div v-if="cards.length" class="p-4">
                <div
                    v-for="(card, index) in cards"
                    :key="index"
                    class="
                        flex
                        justify-between
                        bg-white
                        rounded-md
                        w-full
                        py-2
                        px-4
                        mb-2
                    "
                >
                    <img
                        :src="card.image_url"
                        :alt="card.name"
                        class="h-48 px-4"
                    />
                    <div class="flex flex-col justify-between">
                        <div class="text-center">
                            <p>{{ card.name }} - {{ card.set_name }}</p>
                            <p class="text-sm text-gray-500">
                                {{ card.feature }}
                            </p>
                        </div>
                        <div class="flex justify-center py-4">
                            <VerticalIncrementer
                                v-if="card.hasNonFoil"
                                class="mx-4"
                                label="NonFoil"
                                :model-value="card.nonfoil_collected"
                                :active="
                                    activeField === index &&
                                    activeFieldType === 'NonFoil'
                                "
                                @activate="activate(index, 'NonFoil')"
                                @update:model-value="
                                    updateQuantity($event, card.id, false)
                                "
                            />
                            <VerticalIncrementer
                                v-if="card.hasFoil"
                                class="mx-4"
                                label="Foil"
                                :model-value="card.foil_collected"
                                :active="
                                    activeField === index &&
                                    activeFieldType === 'Foil'
                                "
                                @activate="activate(index, 'Foil')"
                                @update:model-value="
                                    updateQuantity($event, card.id, true)
                                "
                            />
                        </div>
                    </div>
                    <div>
                        <p class="text-right">
                            <span class="text-sm text-gray-500 mr-2"
                                >Non-Foil</span
                            >
                            <span v-if="card.hasNonFoil">{{
                                card.price ? format(card.price) : "N/A"
                            }}</span>
                            <span v-else>N/A</span>
                        </p>
                        <p class="text-right">
                            <span class="text-sm text-gray-500 mr-2">Foil</span>
                            <span v-if="card.hasFoil">{{
                                card.price_foil
                                    ? format(card.price_foil)
                                    : "N/A"
                            }}</span>
                            <span v-else>N/A</span>
                        </p>
                    </div>
                </div>
            </div>
            <DataGridPagination :pagination="pagination" />
        </div>
    </div>
</template>

<script>
import { formatCurrency } from "@/Shared/api/ConvertValue";
import VerticalIncrementer from "@/Components/Buttons/VerticalIncrementer";
import DataGridPagination from "@/Components/DataGrid/DataGridPagination";

export default {
    name: "CardSetSearchResults",

    components: { DataGridPagination, VerticalIncrementer },

    props: {
        pagination: {
            type: Object,
            default: () => {},
        },
    },

    data() {
        return {
            activeField: null,
            activeFieldType: null,
            editingCache: 0,
        };
    },

    computed: {
        cards() {
            return this.$store.getters.cardSearchResults;
        },
        sets() {
            return this.$store.getters.setSearchResults;
        },
    },

    methods: {
        format(value) {
            return value ? formatCurrency(value) : "N/A";
        },
        activate(id, type) {
            this.activeField = id;
            this.activeFieldType = type;
        },
        updateQuantity: function (value, id, foil) {
            this.activeField = null;
            this.activeFieldType = null;
            let key = foil ? "foil_collected" : "nonfoil_collected";
            let change = value - this.cards.find((card) => card.id === id)[key];
            if (change === 0) {
                return;
            }
            this.emitter.emit("updateCardQuantity", {
                change: change,
                id: id,
                foil: foil,
            });
        },
    },
};
</script>
