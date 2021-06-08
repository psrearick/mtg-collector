<template>
    <p v-if="field.type === 'text' || field.type === 'array'">
        {{ fieldValue }}
    </p>
</template>

<script>
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
        fieldValue() {
            if (this.field.type === "array") {
                return this.data[this.field.key].join(", ");
            }
            return this.data[this.field.key];
        },
    },

    methods: {
        click() {
            this.$emit("click");
        },
    },
};
</script>
