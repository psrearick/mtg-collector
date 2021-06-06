<template>
    <inertia-link
        :href="href"
        :class="classes"
        :method="method"
        :as="linkType"
    >
        <slot />
    </inertia-link>
</template>

<script>
export default {
    name: "MobileMenuItem",

    props: {
        href: {
            type: String,
            default: "#",
        },
        current: {
            type: Boolean,
            default: false,
        },
        method: {
            type: String,
            default: "get",
        },
        linkType: {
            type: String,
            default: "button",
        },
    },

    computed: {
        classes() {
            return (
                "block w-full text-left px-3 py-2 rounded-md text-base font-medium " +
                (this.current
                    ? "bg-gray-900 text-white"
                    : "text-gray-300 hover:bg-gray-700 hover:text-white")
            );
        },
    },
};
</script>

<style scoped></style>
