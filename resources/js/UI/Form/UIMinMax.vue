<template>
    <div>
        <ui-input-label :name="field.key" :label="field.label" />
        <ui-input
            v-model="valueMin"
            :name="'minimum-' + field.key"
            :type="type"
            label="Minimum"
            placeholder="Min..."
            :step="0.01"
            :min="0"
            :before="before"
        />
        <ui-input
            v-model="valueMax"
            :name="'maximum-' + field.key"
            :type="type"
            label="Maximum"
            placeholder="Max..."
            :step="0.01"
            :min="parseFloat(valueMin)"
            :before="before"
        />
    </div>
</template>
<script>
import UiInputLabel from "@/UI/Form/UIInputLabel";
import UiInput from "@/UI/Form/UIInput";
export default {
    name: "UiMinMax",

    components: { UiInputLabel, UiInput },

    props: {
        modelValue: {
            type: Object,
            default: () => {},
        },
        field: {
            type: Object,
            default: () => {},
        },
    },
    emits: ["update:modelValue"],

    computed: {
        before() {
            if (this.field.uiComponentOptions) {
                let componentType = this.field.uiComponentOptions.type;
                if (!componentType) {
                    return null;
                }

                if (componentType === "currency") {
                    return "$";
                }
            }
            return null;
        },
        type() {
            if (this.field.uiComponentOptions) {
                let componentType = this.field.uiComponentOptions.type;
                if (!componentType) {
                    return null;
                }

                if (componentType === "currency") {
                    return "number";
                }

                return componentType;
            }
            return null;
        },
        value: {
            get() {
                return this.modelValue
                    ? this.modelValue
                    : {
                          min: null,
                          max: null,
                      };
            },
            set(value) {
                this.$emit("update:modelValue", value);
            },
        },
        valueMin: {
            get() {
                return this.value["min"];
            },
            set(value) {
                const val = this.value;
                val["min"] = value;
                this.value = val;
            },
        },
        valueMax: {
            get() {
                return this.value["max"];
            },
            set(value) {
                const val = this.value;
                val["max"] = value;
                this.value = val;
            },
        },
    },
};
</script>
