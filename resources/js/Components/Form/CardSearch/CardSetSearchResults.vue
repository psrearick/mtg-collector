<template>
    <div class="w-full">
        <div
            v-if="cards || sets.length"
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
                            <p>{{ card.name }} - {{ card.set.name }}</p>
                            <p class="text-sm text-gray-500">
                                {{ card.feature }}
                            </p>
                        </div>
                        <div class="flex justify-center py-4">
                            <div
                                v-if="card.hasNonFoil"
                                class="p-2 mx-4 shadow-md bg-gray-100 w-32"
                            >
                                <button
                                    class="
                                        p-2
                                        bg-green-500
                                        hover:bg-green-900
                                        text-white
                                        w-full
                                    "
                                    @click="
                                        emitter.emit('updateCardQuantity', {
                                            change: 1,
                                            id: card.id,
                                            foil: false,
                                        })
                                    "
                                >
                                    +
                                </button>
                                <div
                                    class="
                                        text-2xl text-center
                                        bg-white
                                        w-full
                                        py-1
                                    "
                                >
                                    <input
                                        v-if="
                                            activeField === index &&
                                            activeFieldType === 'NonFoil'
                                        "
                                        :ref="'NonFoil-input-' + index"
                                        v-model="card.collectionQuantityNonFoil"
                                        type="number"
                                        step="1"
                                        min="0"
                                        class="
                                            no-buttons
                                            w-full
                                            text-center
                                            -ml-px
                                            inline-flex
                                            items-center
                                            px-4
                                            py-1
                                            border border-gray-300
                                            focus:border-gray-300
                                            focus:ring-0 focus:ring-transparent
                                            bg-white
                                            text-sm
                                            font-medium
                                            text-gray-700
                                        "
                                        tabindex="0"
                                        @blur="
                                            doneEditQuantity(
                                                index,
                                                'NonFoil',
                                                card.collectionQuantityNonFoil
                                            )
                                        "
                                        @keyup.enter="
                                            doneEditQuantity(
                                                index,
                                                'NonFoil',
                                                card.collectionQuantityNonFoil
                                            )
                                        "
                                        @keyup.esc="
                                            cancelEditQuantity(index, 'NonFoil')
                                        "
                                    />
                                    <p
                                        v-else
                                        @click="
                                            editQuantity(
                                                index,
                                                'NonFoil',
                                                card.collectionQuantityNonFoil
                                            )
                                        "
                                    >
                                        {{ card.collectionQuantityNonFoil }}
                                    </p>
                                </div>
                                <button
                                    class="
                                        p-2
                                        bg-blue-500
                                        hover:bg-blue-900
                                        text-white
                                        w-full
                                    "
                                    @click="
                                        emitter.emit('updateCardQuantity', {
                                            change: -1,
                                            id: card.id,
                                            foil: false,
                                        })
                                    "
                                >
                                    -
                                </button>
                                <p
                                    class="
                                        w-full
                                        text-center
                                        capitalize
                                        text-gray-700
                                    "
                                >
                                    Non-Foil
                                </p>
                            </div>
                            <div
                                v-if="card.hasFoil"
                                class="p-2 mx-4 shadow-md bg-gray-100 w-32"
                            >
                                <button
                                    class="
                                        p-2
                                        bg-green-500
                                        hover:bg-green-900
                                        text-white
                                        w-full
                                    "
                                    @click="
                                        emitter.emit('updateCardQuantity', {
                                            change: 1,
                                            id: card.id,
                                            foil: true,
                                        })
                                    "
                                >
                                    +
                                </button>
                                <div class="text-2xl text-center bg-white py-1">
                                    <input
                                        v-if="
                                            activeField === index &&
                                            activeFieldType === 'Foil'
                                        "
                                        :ref="'Foil-input-' + index"
                                        v-model="card.collectionQuantityFoil"
                                        type="number"
                                        step="1"
                                        min="0"
                                        class="
                                            no-buttons
                                            w-full
                                            text-center
                                            -ml-px
                                            inline-flex
                                            items-center
                                            px-4
                                            py-1
                                            border border-gray-300
                                            focus:border-gray-300
                                            focus:ring-0 focus:ring-transparent
                                            bg-white
                                            text-sm
                                            font-medium
                                            text-gray-700
                                        "
                                        tabindex="0"
                                        @blur="
                                            doneEditQuantity(
                                                index,
                                                'Foil',
                                                card.collectionQuantityFoil
                                            )
                                        "
                                        @keyup.enter="
                                            doneEditQuantity(
                                                index,
                                                'Foil',
                                                card.collectionQuantityFoil
                                            )
                                        "
                                        @keyup.esc="
                                            cancelEditQuantity(index, 'Foil')
                                        "
                                    />
                                    <p
                                        v-else
                                        @click="
                                            editQuantity(
                                                index,
                                                'Foil',
                                                card.collectionQuantityFoil
                                            )
                                        "
                                    >
                                        {{ card.collectionQuantityFoil }}
                                    </p>
                                </div>
                                <button
                                    class="
                                        p-2
                                        bg-blue-500
                                        hover:bg-blue-900
                                        text-white
                                        w-full
                                    "
                                    @click="
                                        emitter.emit('updateCardQuantity', {
                                            change: -1,
                                            id: card.id,
                                            foil: true,
                                        })
                                    "
                                >
                                    -
                                </button>
                                <p
                                    class="
                                        w-full
                                        text-center
                                        capitalize
                                        text-gray-700
                                    "
                                >
                                    Foil
                                </p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="text-right">
                            <span class="text-sm text-gray-500 mr-2"
                                >Non-Foil</span
                            >
                            <span v-if="card.hasNonFoil">{{
                                card.price_normal
                                    ? format(card.price_normal.price)
                                    : "N/A"
                            }}</span>
                            <span v-else>N/A</span>
                        </p>
                        <p class="text-right">
                            <span class="text-sm text-gray-500 mr-2">Foil</span>
                            <span v-if="card.hasFoil">{{
                                card.price_foil
                                    ? format(card.price_foil.price)
                                    : "N/A"
                            }}</span>
                            <span v-else>N/A</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import formatCurrency from "@/Shared/api/ConvertValue";

export default {
    name: "CardSetSearchResults",

    data() {
        return {
            activeField: null,
            activeFieldType: null,
            editingCache: 0,
        };
    },

    computed: {
        cards() {
            return this.$store.getters.cardSearchResults.data;
        },
        sets() {
            return this.$store.getters.setSearchResults;
        },
    },

    methods: {
        format(value) {
            return value ? formatCurrency(value) : "N/A";
        },
        editQuantity(index, type, value) {
            this.editingCache = value;
            this.activeField = index;
            this.activeFieldType = type;
            this.$nextTick(() => {
                this.$refs[type + "-input-" + index].focus();
            });
        },
        doneEditQuantity(index, type, value) {
            this.activeField = null;
            this.activeFieldType = null;
            let parsed = this.limitNumber(value, this.editingCache, 0, 0, 0);
            this.cards[index]["collectionQuantity" + type] = parsed;
            this.emitter.emit("updateCardQuantity", {
                quantity: parsed,
                id: this.cards[index].id,
                foil: type === "Foil",
            });
        },
        cancelEditQuantity(index, type) {
            this.cards[index]["collectionQuantity" + type] = this.editingCache;
            this.editingCache = 0;
        },
        limitNumber: function (val, oldVal, decimals, min, max) {
            if (isNaN(val)) {
                return oldVal;
            }
            let maximum = max > 0 ? Math.min(val, max) : val;
            return Math.max(maximum, min).toFixed(decimals);
        },
    },
};
</script>
