<template>
    <div class="w-full">
        <div
            v-if="cards.length || sets.length"
            class="rounded-md mt-4 p-4 bg-gray-500"
        >
            <div v-if="cards.length" class="p-4">
                <button
                    v-for="(card, index) in cards"
                    :key="index"
                    class="
                        block
                        flex
                        bg-gray-100
                        rounded-md
                        w-full
                        py-2
                        px-4
                        mb-2
                    "
                >
                    <img :src="card.image_url" :alt="card.name" class="h-48 px-4" />
                    <span> {{ card.name }} - {{ card.set.name }} <br /> {{ card.feature }} </span>
                </button>
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
};
</script>
