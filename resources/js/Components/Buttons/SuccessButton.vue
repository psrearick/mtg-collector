<template>
    <Button
        :type="type"
        :href="href"
        classes="bg-green-600 focus:shadow-outline-green focus:border-green-900 active:bg-green-900 hover:bg-green-900 border border-transparent text-white"
    >
        <slot />
        {{ text }}
    </Button>
</template>

<script>
import Button from "@/Components/Buttons/Button";
export default {
    name: "SuccessButton",
    components: { Button },
    props: {
        type: {
            type: String,
            default: "submit",
        },
        text: {
            type: String,
            default: "",
        },
        href: {
            type: String,
            default: "",
        },
    },
};
</script>
