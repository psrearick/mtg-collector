<template>
    <ul
        class="
            absolute
            z-10
            mt-1
            w-full
            bg-white
            shadow-lg
            max-h-60
            rounded-md
            py-1
            text-base
            ring-1 ring-black ring-opacity-5
            overflow-auto
            focus:outline-none
            sm:text-sm
        "
        tabindex="-1"
        role="listbox"
        aria-labelledby="listbox-label"
    >
        <slot />
    </ul>
</template>

<script>
export default {
    name: "SearchSelectOptions",
};
</script>

<style scoped></style>
