<template>
    <button
        :type="type"
        :disabled="disabled"
        :class="
            style +
            ' ' +
            flexType +
            ' items-center px-4 py-2 shadow-sm text-sm font-medium rounded-md focus:outline-none'
        "
    >
        <slot>{{ text }}</slot>
    </button>
</template>

<script>
export default {
    name: "UiButton",

    props: {
        text: {
            type: String,
            default: "",
        },
        buttonStyle: {
            type: String,
            default: "default",
        },
        flexType: {
            type: String,
            default: "",
        },
        type: {
            type: String,
            default: "button",
        },
        disabled: {
            type: Boolean,
            default: false,
        },
    },

    computed: {
        style() {
            if (this.buttonStyle === "primary") {
                return "border border-primary-200 text-gray-700 bg-primary-400 hover:bg-primary-500";
            }
            if (this.buttonStyle === "primary-outline") {
                return "border border-primary-400 text-primary-500 bg-white hover:bg-primary-500 hover:text-primary-800";
            }
            if (this.buttonStyle === "primary-dark") {
                return "border border-transparent focus:border-blue-900 hover:bg-blue-900 text-white bg-blue-600";
            }
            if (this.buttonStyle === "success-outline") {
                return "border border-success-400 text-success-500 bg-white hover:bg-success-500 hover:text-success-800";
            }
            if (this.buttonStyle === "secondary") {
                return "border border-secondary-200 text-gray-700 bg-secondary-400 hover:bg-secondary-500";
            }
            if (this.buttonStyle === "warning") {
                return "border border-warning-200 text-gray-700 bg-warning-300 hover:bg-warning-500";
            }
            if (this.buttonStyle === "danger") {
                return "border border-danger-200 text-black bg-danger-500 hover:bg-danger-700";
            }
            if (this.buttonStyle === "white") {
                return "border border-gray-300 text-gray-700 bg-white hover:bg-gray-300 hover:text-gray-800";
            }
            return "border border-gray-200 text-gray-700 bg-gray-100 hover:bg-gray-300";
        },
    },
};
</script>
