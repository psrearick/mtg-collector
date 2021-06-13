<template>
    <fieldset>
        <label :for="fieldName" class="block text-sm font-medium text-gray-700">
            {{ label }}
            <span v-if="required" class="text-red-600">(Required)</span>
        </label>
        <div class="mt-1">
            <input
                :id="fieldId"
                :value="modelValue"
                :type="type"
                :name="fieldName"
                :autocomplete="fieldName"
                :required="required"
                :aria-required="required"
                class="
                    shadow-sm
                    focus:ring-indigo-500
                    focus:border-indigo-500
                    block
                    w-full
                    sm:text-sm
                    border-gray-300
                    rounded-md
                "
                @input="$emit('update:modelValue', $event.target.value)"
            />
            <p v-if="helperText" class="mt-2 text-sm text-gray-500">
                {{ helperText }}
            </p>
        </div>
    </fieldset>
</template>

<script>
export default {
    name: "Input",

    props: {
        modelValue: {
            type: String,
            default: "",
        },
        label: {
            type: String,
            default: "",
        },
        fieldId: {
            type: String,
            default: "",
        },
        type: {
            type: String,
            default: "text",
        },
        fieldName: {
            type: String,
            default: "",
        },
        required: {
            type: Boolean,
            default: false,
        },
        helperText: {
            type: String,
            default: "",
        },
    },
    emits: ["update:modelValue"],
};
</script>
>
