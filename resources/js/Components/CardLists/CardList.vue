<template>
    <div
        class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3"
        :class="classes"
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
    },
};
</script>
