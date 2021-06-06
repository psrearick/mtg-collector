<template>
    <button
        :aria-expanded="show"
        aria-controls="mobile-menu"
        class="
            inline-flex
            items-center
            justify-center
            p-2
            rounded-md
            text-gray-400
            hover:text-white
            hover:bg-gray-700
            focus:outline-none
            focus:ring-2 focus:ring-inset focus:ring-white
        "
        type="button"
    >
        <span class="sr-only">Open main menu</span>
        <!--
          Icon when menu is closed.
          Menu open: "hidden", Menu closed: "block"
        -->
        <Icon icon="outline-menu" :classes="{ block: !show, hidden: show }" />

        <!--
          Icon when menu is open.
          Menu open: "block", Menu closed: "hidden"
        -->
        <Icon icon="outline-x" :classes="{ hidden: !show, block: show }" />
    </button>
</template>

<script>
import Icon from "@/Components/Icon";

export default {
    name: "MobileMenuButton",

    components: { Icon },

    props: {
        show: {
            type: Boolean,
            default: false,
        },
    },
};
</script>

<style scoped></style>
