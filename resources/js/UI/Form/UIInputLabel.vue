<template>
    <label :id="id" :for="name" class="block text-sm font-medium text-gray-500">
        {{ label }}
        <span v-if="required" class="text-xs text-danger-500">(required)</span>
    </label>
</template>

<script>
export default {
    name: "UiInputLabel",

    props: {
        id: {
            type: String,
            default: "",
        },
        name: {
            type: String,
            default: "",
        },
        label: {
            type: String,
            default: "",
        },
        required: {
            type: Boolean,
            default: false,
        },
    },
};
</script>
