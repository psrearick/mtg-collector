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
                        :src="card.imagePath"
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
                                    @click="
                                        emitter.emit('updateCardQuantity', {
                                            change: 1,
                                            id: card.id,
                                            foil: false,
                                        })
                                    "
                                    class="
                                        p-2
                                        bg-green-500
                                        hover:bg-green-900
                                        text-white
                                        w-full
                                    "
                                >
                                    +
                                </button>
                                <p class="text-2xl text-center bg-white w-full">
                                    0
                                </p>
                                <button
                                    @click="
                                        emitter.emit('updateCardQuantity', {
                                            change: -1,
                                            id: card.id,
                                            foil: false,
                                        })
                                    "
                                    class="
                                        p-2
                                        bg-blue-500
                                        hover:bg-blue-900
                                        text-white
                                        w-full
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
                                    @click="
                                        emitter.emit('updateCardQuantity', {
                                            change: 1,
                                            id: card.id,
                                            foil: true,
                                        })
                                    "
                                    class="
                                        p-2
                                        bg-green-500
                                        hover:bg-green-900
                                        text-white
                                        w-full
                                    "
                                >
                                    +
                                </button>
                                <p class="text-2xl text-center bg-white w-full">
                                    0
                                </p>
                                <button
                                    @click="
                                        emitter.emit('updateCardQuantity', {
                                            change: -1,
                                            id: card.id,
                                            foil: true,
                                        })
                                    "
                                    class="
                                        p-2
                                        bg-blue-500
                                        hover:bg-blue-900
                                        text-white
                                        w-full
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
                                format(card.price_normal)
                            }}</span>
                            <span v-else>N/A</span>
                        </p>
                        <p class="text-right">
                            <span class="text-sm text-gray-500 mr-2">Foil</span>
                            <span v-if="card.hasFoil">{{
                                format(card.price_foil)
                            }}</span>
                            <span v-else>N/A</span>
                        </p>
                    </div>
                </div>
            </div>
            <div v-if="!cards.length" class="p-4">
                <button
                    v-for="(set, index) in sets"
                    :key="index"
                    class="block bg-gray-100 rounded-md w-full py-2 px-4 mb-2"
                >
                    {{ set.name }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import formatCurrency from "@/Shared/api/ConvertValue";

export default {
    name: "CardSetSearchResults",

    props: {
        cards: {
            type: Array,
            default: () => {},
        },
        sets: {
            type: Array,
            default: () => {},
        },
    },

    methods: {
        format(value) {
            return value ? formatCurrency(value) : "N/A";
        },
    },
};
</script>
