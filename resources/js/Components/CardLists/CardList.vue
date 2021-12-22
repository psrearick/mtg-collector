<template>
    <div
        :class="
            'grid grid-cols-1 gap-4 sm:grid-cols-2 ' +
            maxClass() +
            ' ' +
            classes
        "
    >
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: "CardList",
    props: {
        classes: {
            type: String,
            default: "",
        },
        maxCols: {
            type: String,
            default: "3",
        },
    },
    methods: {
        maxClass() {
            return "md:grid-cols-" + this.maxCols;
        },
    },
};
</script>
