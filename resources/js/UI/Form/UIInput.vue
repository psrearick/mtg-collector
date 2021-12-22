<template>
    <div class="mt-1">
        <div>
            <ui-input-label :name="name" :label="label" :required="required" />
            <div class="mt-1 relative rounded-md shadow-sm">
                <div
                    v-if="before"
                    class="
                        absolute
                        inset-y-0
                        left-0
                        pl-3
                        flex
                        items-center
                        pointer-events-none
                    "
                >
                    <span class="text-gray-500 sm:text-sm">
                        {{ before }}
                    </span>
                </div>
                <input
                    :id="name"
                    :type="getType()"
                    :name="name"
                    :class="`
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
                        disabled:bg-gray-100
                    `"
                    :placeholder="placeholder"
                    :autocomplete="autocomplete"
                    :required="required"
                    :autofocus="autofocus"
                    :disabled="disabled"
                    :value="value"
                    :min="min"
                    :max="max"
                    :step="step"
                    @input="$emit('update:modelValue', $event.target.value)"
                    @blur="$emit('blur', $event.target.value)"
                />
                <div
                    v-if="after"
                    class="
                        absolute
                        inset-y-0
                        right-0
                        pr-3
                        flex
                        items-center
                        pointer-events-none
                    "
                >
                    <span id="price-currency" class="text-gray-500 sm:text-sm">
                        {{ after }}
                    </span>
                </div>
            </div>
            <div v-if="errorMessage">
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
    name: "UiInput",
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
        type: {
            type: String,
            default: "input",
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
        autofocus: {
            type: Boolean,
            default: false,
        },
        autocomplete: {
            type: String,
            default: "",
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
        min: {
            type: Number,
            default: null,
        },
        max: {
            type: Number,
            default: null,
        },
        step: {
            type: Number,
            default: 1,
        },
        before: {
            type: String,
            default: null,
        },
        after: {
            type: String,
            default: null,
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
            let thisClass =
                "w-full appearance-none block px-3 py-2 border border-gray-300 shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm";
            if (this.type === "currency") {
                return "col-span-7 rounded-r-md " + thisClass;
            }
            if (this.type === "percent") {
                return "col-span-7 rounded-l-md " + thisClass;
            }
            return "col-span-8 rounded-md " + thisClass;
        },
        getType() {
            if (this.type === "currency" || this.type === "percent") {
                return "text";
            }
            return this.type;
        },
    },
};
</script>
