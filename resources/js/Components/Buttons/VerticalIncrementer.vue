<template>
    <div class="p-2 shadow-md bg-gray-100 w-32">
        <button
            class="p-2 bg-success-500 hover:bg-green-900 text-white w-full"
            @click="$emit('update:modelValue', modelValue + 1)"
        >
            +
        </button>
        <div class="text-2xl text-center bg-white py-1">
            <input
                v-if="active"
                :ref="'input'"
                v-model="value"
                type="number"
                step="1"
                min="0"
                class="
                    no-buttons
                    w-full
                    text-center
                    -ml-px
                    inline-flex
                    items-center
                    px-4
                    py-1
                    border border-gray-300
                    focus:border-gray-300
                    focus:ring-0 focus:ring-transparent
                    bg-white
                    text-sm
                    font-medium
                    text-gray-700
                "
                tabindex="0"
                @blur="doneEditQuantity(value)"
                @keyup.enter.prevent="doneEditQuantity(value)"
                @keyup.esc.prevent="cancelEditQuantity()"
            />
            <p v-else @click="editQuantity(value)">
                {{ value }}
            </p>
        </div>
        <button
            class="p-2 bg-blue-500 hover:bg-blue-900 text-white w-full"
            @click="$emit('update:modelValue', modelValue - 1)"
        >
            -
        </button>
        <p v-if="label" class="w-full text-center capitalize text-gray-700">
            {{ label }}
        </p>
    </div>
</template>

<script>
export default {
    name: "VerticalIncrementer",

    props: {
        modelValue: {
            type: Number,
            default: 0,
        },
        active: {
            type: Boolean,
            default: false,
        },
        label: {
            type: String,
            default: "",
        },
        decimals: {
            type: Number,
            default: 0,
        },
        max: {
            type: Number,
            default: 0,
        },
        min: {
            type: Number,
            default: 0,
        },
    },

    emits: ["update:modelValue", "activate"],

    data() {
        return {
            value: 0,
            editingCache: 0,
        };
    },

    watch: {
        active(val) {
            if (val) {
                this.$nextTick(() => {
                    this.$refs["input"].focus();
                });
            }
        },
        modelValue(val) {
            this.value = val;
        },
    },

    mounted() {
        this.value = this.modelValue;
    },

    methods: {
        editQuantity(value) {
            this.editingCache = value;
            this.$emit("activate");
        },
        doneEditQuantity(value) {
            this.value = this.limitNumber(
                value,
                this.editingCache,
                this.decimals,
                this.min,
                this.max
            );
            this.$emit("update:modelValue", parseFloat(this.value));
        },
        cancelEditQuantity() {
            this.value = this.editingCache;
            this.editingCache = 0;
            this.$emit("update:modelValue", parseFloat(this.value));
        },
        limitNumber: function (val, oldVal, decimals, min, max) {
            if (isNaN(val)) {
                return oldVal;
            }
            let maximum = max > 0 ? Math.min(val, max) : val;
            return Math.max(maximum, min).toFixed(decimals);
        },
    },
};
</script>
