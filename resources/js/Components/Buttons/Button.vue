<template>
    <button
        :type="type"
        class="
            inline-flex
            items-center
            px-4
            py-2
            rounded-md
            font-semibold
            text-xs
            uppercase
            tracking-widest
            focus:outline-none
            transition
            ease-in-out
            duration-150
        "
        :class="classes"
    >
        <slot />
    </button>
</template>

<script>
export default {
    props: {
        type: {
            type: String,
            default: "submit",
        },
        classes: {
            type: String,
            default:
                "bg-gray-800 focus:shadow-outline-gray focus:border-gray-900 active:bg-gray-900 hover:bg-gray-700 border border-transparent text-white",
        },
    },
};
</script>
