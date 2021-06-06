<template>
    <div
        class="
            w-48
            rounded-md
            shadow-lg
            py-1
            bg-white
            ring-1 ring-black ring-opacity-5
            focus:outline-none
        "
        role="menu"
        aria-orientation="vertical"
        aria-labelledby="user-menu-button"
        tabindex="-1"
    >
        <slot />
    </div>
</template>

<script>
export default {
    name: "MenuItems",
};
</script>
