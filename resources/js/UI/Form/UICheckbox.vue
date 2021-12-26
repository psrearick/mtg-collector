<template>
    <div>
        <input
            :id="name"
            v-model="proxyChecked"
            :name="name"
            type="checkbox"
            :value="value"
            class="
                rounded
                border-gray-300
                text-primary-600
                shadow-sm
                focus:border-primary-300
                focus:ring focus:ring-primary-200 focus:ring-opacity-50
            "
        />
        <span class="ml-2 text-sm text-gray-600">{{ label }}</span>
        <slot />
    </div>
</template>

<script>
export default {
    name: "UiCheckbox",

    props: {
        name: {
            type: String,
            default: "",
        },
        checked: {
            type: [Array, Boolean],
            default: false,
        },
        value: {
            type: [Array, Boolean],
            default: null,
        },
        label: {
            type: String,
            default: "",
        },
    },

    emits: ["update:checked"],

    computed: {
        proxyChecked: {
            get() {
                return this.checked;
            },

            set(val) {
                this.$emit("update:checked", val);
            },
        },
    },
};
</script>
