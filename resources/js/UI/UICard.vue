<template>
    <div :class="classes">
        <slot />
    </div>
</template>

<script>
export default {
    name: "UiCard",

    props: {
        mode: {
            type: String,
            default: "default",
        },
    },

    computed: {
        classes() {
            let classes = "py-8 px-4 sm:rounded-lg sm:px-10 m-4";

            if (this.mode === "default") {
                classes += " bg-gray-200 border border-gray-500";
            }

            if (this.mode === "danger") {
                classes += " border-red-500 border bg-red-200";
            }

            return classes;
        },
    },
};
</script>
