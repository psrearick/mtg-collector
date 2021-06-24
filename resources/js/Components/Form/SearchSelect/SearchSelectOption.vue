<template>
    <li
        id="listbox-option-0"
        :class="cardListItemClasses"
        role="option"
        @mouseenter="highlight = true"
        @mouseleave="highlight = false"
        @click="$emit('selectOption')"
    >
        <div class="flex">
            <span :class="primaryTextClasses"> {{ option.primary }} </span>
            <span :class="secondaryTextClasses">
                {{ secondaryText }}
            </span>
        </div>
        <span v-if="highlight" :class="iconClasses">
            <Icon icon="check" />
        </span>
    </li>
</template>

<script>
import Icon from "@/Components/Icon";

export default {
    name: "SearchSelectOption",

    components: { Icon },

    props: {
        selected: {
            type: Boolean,
            default: false,
        },
        option: {
            type: Object,
            default: () => {},
        },
    },

    emits: ["selectOption"],

    data() {
        return {
            highlight: false,
        };
    },

    computed: {
        cardListItemClasses() {
            const classes =
                "cursor-default select-none relative py-2 pl-3 pr-9";
            const selectedClasses = this.highlight
                ? "text-white bg-indigo-600"
                : "text-gray-900";
            return classes + " " + selectedClasses;
        },
        primaryTextClasses() {
            const classes = "truncate";
            const selectedClasses = this.highlight
                ? "font-semibold"
                : "font-normal";
            return classes + " " + selectedClasses;
        },
        secondaryTextClasses() {
            const classes = "truncate ml-2";
            const selectedClasses = this.highlight
                ? "text-indigo-200"
                : "text-gray-500";
            return classes + " " + selectedClasses;
        },
        secondaryText() {
            return this.option.secondary && this.option.secondary.length
                ? " (" + this.option.secondary + ")"
                : "";
        },
        iconClasses() {
            const classes = "absolute inset-y-0 right-0 flex items-center pr-4";
            const selectedClasses = this.highlight
                ? "text-white"
                : "text-indigo-600";
            return classes + " " + selectedClasses;
        },
    },

    mounted() {
        this.highlight = this.selected;
    },
};
</script>

<style scoped></style>
