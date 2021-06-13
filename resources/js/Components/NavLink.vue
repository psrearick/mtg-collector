<template>
    <inertia-link :href="href" :class="classes">
        <slot />
    </inertia-link>
</template>

<script>
export default {
    props: {
        href: { type: String, default: "" },
        active: { type: Boolean, default: false },
    },

    computed: {
        classes() {
            return this.active
                ? "bg-gray-900 text-blue-500 px-3 py-2 rounded-md text-sm font-medium"
                : "text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium";
        },
    },
};
</script>
