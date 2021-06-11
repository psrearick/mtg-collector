<template>
    <p v-if="field.type === 'text' || field.type === 'array'">
        {{ fieldValue }}
    </p>
    <p v-if="field.type === 'currency'">
        {{ fieldValue }}
    </p>
</template>

<script>
import formatCurrency from "@/shared/api/ConvertValue";

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

    setup(props) {
        let value = props.data[props.field.key];
        if (value && props.field.type === "currency") {
            value = formatCurrency(value);
        }
        return {
            value,
        };
    },

    computed: {
        fieldValue() {
            if (this.field.type === "array") {
                return this.value.join(", ");
            }
            return this.value;
        },
    },

    methods: {
        click() {
            this.$emit("click");
        },
    },
};
</script>
