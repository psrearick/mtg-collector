<template>
    <button
        class="
            flex-shrink-0
            bg-gray-800
            p-1
            rounded-full
            text-gray-400
            hover:text-white
            focus:outline-none
            focus:ring-2
            focus:ring-offset-2
            focus:ring-offset-gray-800
            focus:ring-white
        "
    >
        <span class="sr-only">View notifications</span>
        <!-- Heroicon name: outline/bell -->
        <Icon icon="outline-bell" />
    </button>
</template>

<script>
import Icon from "@/Components/Icon";

export default {
    name: "NotificationButton",

    components: { Icon },
};
</script>
