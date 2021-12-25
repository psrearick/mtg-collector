<template>
    <div class="mt-1">
        <div>
            <ui-input-label :name="name" :label="label" :required="required" />
            <div class="mt-1 relative rounded-md shadow-sm">
                <textarea
                    :id="name"
                    v-model="value"
                    :name="name"
                    class="
                        border
                        py-2
                        focus:ring-primary-500
                        focus:border-primary-500
                        block
                        w-full
                        px-4
                        sm:text-sm
                        border-gray-300
                        rounded-md
                    "
                    :placeholder="placeholder"
                    :autocomplete="name"
                    :required="required"
                    :disabled="disabled"
                    @input="$emit('update:modelValue', $event.target.value)"
                    @blur="$emit('blur', $event.target.value)"
                />
            </div>
            <div>
                <span class="text-red-500 text-xs">
                    {{ errorMessage }}
                </span>
            </div>
        </div>
    </div>
</template>

<script>
import UiInputLabel from "@/UI/Form/UIInputLabel";
export default {
    name: "UiTextArea",
    components: { UiInputLabel },
    props: {
        modelValue: {
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
        placeholder: {
            type: String,
            default: "",
        },
        required: {
            type: Boolean,
            default: false,
        },
        formatter: {
            type: Function,
            default: (value) => {
                return value;
            },
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        errorMessage: {
            type: String,
            default: "",
        },
    },

    emits: ["update:modelValue", "blur"],

    computed: {
        value() {
            if (!this.formatter) {
                return this.modelValue;
            }
            return this.formatter(this.modelValue);
        },
    },

    methods: {
        focus() {
            this.$refs.input.focus();
        },
        getClass() {
            return "col-span-8 rounded-md w-full appearance-none block px-3 py-2 border border-gray-300 shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm";
        },
    },
};
</script>
