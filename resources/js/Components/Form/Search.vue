<template>
    <div class="max-w-lg w-full lg:max-w-xs">
        <label for="search" class="sr-only">Search</label>
        <div class="relative">
            <div
                class="
                    absolute
                    inset-y-0
                    left-0
                    pl-3
                    flex
                    items-center
                    pointer-events-none
                "
            >
                <Icon icon="search" />
            </div>
            <input
                id="search"
                name="search"
                :class="classes"
                placeholder="Search"
                type="search"
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
            />
        </div>
    </div>
</template>

<script>
import Icon from "@/Components/Icon";
export default {
    name: "Search",

    components: { Icon },

    props: {
        dark: {
            type: Boolean,
            default: false,
        },
        modelValue: {
            type: String,
            default: "",
        },
    },
    emits: ["update:modelValue"],

    computed: {
        classes() {
            return (
                "block w-full pl-10 pr-3 py-2 border rounded-md leading-5 sm:text-sm placeholder-gray-400 focus:outline-none focus:ring-white focus:text-gray-900 " +
                (this.dark
                    ? "bg-gray-700 text-gray-300 border-transparent focus:bg-white focus:border-white"
                    : "bg-white border-gray-300 text-gray-700 focus:bg-gray-50 focus:border-gray-500")
            );
        },
    },
};
</script>

<style scoped></style>
