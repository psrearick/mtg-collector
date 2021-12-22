<template>
    <div
        class="
            block
            w-full
            px-4
            py-2
            text-left text-sm
            leading-5
            text-gray-700
            hover:bg-gray-100
            focus:outline-none
            focus:bg-gray-100
            transition
            duration-150
            ease-in-out
            cursor-pointer
        "
        @click="linkClicked"
    >
        <slot />
    </div>
</template>

<script>
export default {
    name: "UiDropdownLink",

    props: {
        click: {
            type: Object,
            default: () => {},
        },
    },

    methods: {
        linkClicked() {
            this.emitter.emit("dropdown_link_click", this.click);
        },
    },
};
</script>
