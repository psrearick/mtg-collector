<template>
    <a :href="href" aria-current="page" :class="classes">
        <slot></slot>
    </a>
</template>

<script>
export default {
    name: "DataGridPaginationButton",

    props: {
        href: {
            type: String,
            default: "#",
        },
        pageIndex: {
            type: Number,
            default: 0,
        },
        active: {
            type: Boolean,
            default: false,
        },
        classAdditions: {
            type: String,
            default: "",
        },
    },
    data() {
        return {
            pageNumber: 0,
        };
    },
    computed: {
        classes() {
            return (
                "relative inline-flex items-center px-4 py-2 border text-sm font-medium" +
                (this.classAdditions ? " " + this.classAdditions + " " : "") +
                (this.active
                    ? " bg-indigo-50 border-indigo-500 text-indigo-600 z-20"
                    : " bg-white border-gray-300 text-gray-500 hover:bg-gray-50 z-10")
            );
        },
    },
};
</script>
