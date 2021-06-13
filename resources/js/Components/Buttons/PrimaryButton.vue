<template>
    <Button
        :type="type"
        classes="bg-blue-600 focus:shadow-outline-blue focus:border-blue-900 active:bg-blue-900 hover:bg-blue-900 border border-transparent text-white"
        ><slot
    /></Button>
</template>

<script>
import Button from "@/Components/Buttons/Button";
export default {
    name: "PrimaryButton",
    components: { Button },
    props: {
        type: {
            type: String,
            default: "submit",
        },
    },
};
</script>
