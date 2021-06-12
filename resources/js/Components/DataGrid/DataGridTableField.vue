<template>
    <p v-if="field.type === 'text' || field.type === 'array'">
        {{ fieldValue }}
    </p>
    <p v-if="field.type === 'currency'">
        {{ fieldValue }}
    </p>
</template>

<script>
import formatCurrency from "@/Shared/api/ConvertValue";

export default {
    name: "DataGridTableField",

    props: {
        field: {
            type: Object,
            default: () => {},
        },
        data: {
            type: Object,
            default: () => {},
        },
    },

    emits: ["click"],

    computed: {
        formattedValue() {
            let value = this.data[this.field.key];
            if (value && this.field.type === "currency") {
                value = formatCurrency(value);
            }
            return value;
        },
        fieldValue() {
            if (this.field.type === "array") {
                return this.formattedValue.join(", ");
            }
            return this.formattedValue;
        },
    },

    methods: {
        click() {
            this.$emit("click");
        },
    },
};
</script>
