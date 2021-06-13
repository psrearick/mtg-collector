<template>
    <div class="mx-auto text-center">
        <Icon icon="logo" :classes="'mx-auto hover:text-blue-500 ' + classes" />
        <p v-if="showText" :class="textClass">MTG Collector</p>
    </div>
</template>

<script>
import Icon from "@/Components/Icon";

export default {
    name: "Logo",
    components: { Icon },
    props: {
        size: {
            type: Number,
            default: 48,
        },
        classes: {
            type: String,
            default: "",
        },
        textClass: {
            type: String,
            default: "text-xl",
        },
        showText: {
            type: Boolean,
            default: true,
        },
    },
};
</script>
