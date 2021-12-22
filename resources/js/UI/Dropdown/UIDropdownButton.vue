<template>
    <div class="relative inline-block text-left">
        <div>
            <button
                id="menu-button"
                type="button"
                :class="
                    classes +
                    ' inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 my-1 text-sm font-medium focus:outline-none'
                "
                aria-expanded="true"
                aria-haspopup="true"
            >
                {{ label }}
                <Icon icon="chevron-down" />
            </button>
        </div>
    </div>
</template>

<script>
import Icon from "@/Components/Icon.vue";

export default {
    name: "UiDropdownButton",

    components: { Icon },

    props: {
        active: {
            type: Boolean,
            default: true,
        },
        label: {
            type: String,
            default: "Options",
        },
    },

    computed: {
        classes() {
            if (!this.active) {
                return "bg-gray-200 text-gray-500";
            }
            return "bg-white text-gray-700 hover:bg-gray-50 focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500";
        },
    },
};
</script>
