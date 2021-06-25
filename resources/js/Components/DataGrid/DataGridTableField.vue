<template>
    <p
        v-if="
            (field.type === 'text' || field.type === 'array') && showField(data)
        "
        :class="data.classes"
    >
        {{ fieldValue }}
    </p>
    <p
        v-if="field.type === 'currency' && showField(data)"
        :class="data.classes"
    >
        {{ fieldValue }}
    </p>
    <p
        v-if="field.type === 'composite-text' && showField(data)"
        :class="data.classes"
    >
        <span
            v-for="(value, index) in field.values"
            v-show="data[value.key]"
            :key="index"
            :class="value.classes"
        >
            {{
                value.type === "currency"
                    ? formatCurrencyOrEmpty(data[value.key])
                    : data[value.key]
            }}
        </span>
    </p>
    <component
        :is="field.component"
        v-if="field.type === 'component' && showField(data)"
        :data="data"
        :field="field"
        :class="data.classes"
    />
</template>

<script>
import { formatCurrency } from "@/Shared/api/ConvertValue";

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
            if (typeof this.field.key === "undefined") {
                return this.field.value;
            }
            let value = this.data[this.field.key];
            if (this.field.type === "currency") {
                return this.formatCurrencyOrEmpty(value);
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
        formatCurrencyOrEmpty(value) {
            if (!value) {
                return "";
            }
            value = formatCurrency(value);
            return value !== "0" ? value : "";
        },
        showField(data) {
            if (this.field.condition) {
                return this.field.condition(data);
            }
            return true;
        },
    },
};
</script>
