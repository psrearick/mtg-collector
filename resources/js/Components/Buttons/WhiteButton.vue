<template>
    <Button
        :type="type"
        classes="bg-white focus:outline-none hover:bg-gray-50 border border-gray-300 text-gray-700 focus:ring-offset-2 focus:ring-blue-500"
        ><slot
    /></Button>
</template>

<script>
import Button from "@/Components/Buttons/Button";
export default {
    name: "WhiteButton",
    components: { Button },
    props: {
        type: {
            type: String,
            default: "submit",
        },
    },
};
</script>
