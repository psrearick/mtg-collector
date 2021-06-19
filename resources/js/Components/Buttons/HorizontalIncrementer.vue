<template>
    <div class="grid grid-cols-3">
        <button
            class="py-2 px-4 bg-gray-200 hover:bg-gray-400 text-center"
            @click="decrement"
        >
            -
        </button>
        <div class="py-2 px-4 bg-white text-center">{{ data.quantity }}</div>
        <button
            class="py-2 px-4 bg-gray-200 hover:bg-gray-400 text-center"
            @click="increment"
        >
            +
        </button>
    </div>
</template>

<script>
export default {
    name: "HorizontalIncrementer",

    props: {
        data: {
            type: Object,
            default: () => {},
        },
        field: {
            type: Object,
            default: () => {},
        },
    },

    methods: {
        increment() {
            this.emitter.emit("incrementQuantity", this.data);
        },
        decrement() {
            this.emitter.emit("decrementQuantity", this.data);
        },
    },
};
</script>
