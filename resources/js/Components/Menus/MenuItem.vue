<template>
    <inertia-link
        :id="id"
        :href="href"
        :method="method"
        :as="linkType"
        class="
            block
            px-4
            py-2
            text-sm text-gray-700
            hover:bg-gray-100
            w-full
            text-left
        "
        :class="{
            'bg-gray-100': current,
        }"
        role="menuitem"
        tabindex="-1"
    >
        <slot />
    </inertia-link>
</template>

<script>
export default {
    name: "MenuItem",

    props: {
        id: {
            type: String,
            default: "",
        },
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
};
</script>
