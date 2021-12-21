<template>
    <button
        id="user-menu-button"
        type="button"
        class="
            bg-gray-800
            flex
            text-sm text-gray-500
            focus:text-gray-200
            hover:text-gray-200
            focus:outline-none
        "
        aria-expanded="false"
        aria-haspopup="true"
    >
        <span class="sr-only">Open user menu</span>
        <Icon icon="avatar" size="2rem" />
    </button>
</template>

<script>
import Icon from "@/Components/Icon";
export default {
    name: "ProfileButton",
    components: { Icon },
};
</script>
