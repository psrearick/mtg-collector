<template>
    <div class="grid grid-cols-3">
        <button :class="buttonClass" @click="decrement">-</button>
        <div :class="numberClass">{{ data.quantity }}</div>
        <button :class="buttonClass" @click="increment">+</button>
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

    computed: {
        buttonClass() {
            let button = "bg-gray-200 hover:bg-gray-400 text-center";
            button +=
                this.field.componentType === "small"
                    ? " py-1 px-1 text-sm"
                    : " py-2 px-4";
            return button;
        },
        numberClass() {
            let numberClass = "bg-white text-center";
            numberClass +=
                this.field.componentType === "small"
                    ? " py-1 px-1 text-sm"
                    : " py-2 px-4";
            return numberClass;
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
