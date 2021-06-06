<template>
    <button
        id="user-menu-button"
        type="button"
        class="
            bg-gray-800
            rounded-full
            flex
            text-sm text-white
            focus:outline-none
            focus:ring-2
            focus:ring-offset-2
            focus:ring-offset-gray-800
            focus:ring-white
        "
        aria-expanded="false"
        aria-haspopup="true"
    >
        <span class="sr-only">Open user menu</span>
        <img
            class="h-8 w-8 rounded-full"
            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixqx=zHlOFbGo5k&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
            alt=""
        />
    </button>
</template>

<script>
export default {
    name: "ProfileButton",
};
</script>

<style scoped></style>
