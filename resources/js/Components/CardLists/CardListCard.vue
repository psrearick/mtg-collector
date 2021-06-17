<template>
    <div :class="classes">
        <div class="flex-shrink-0">
            <slot name="left"></slot>
        </div>
        <div class="flex-1 min-w-0">
            <inertia-link
                v-if="link && href"
                :href="href"
                class="focus:outline-none"
            >
                <slot></slot>
            </inertia-link>
            <div v-else>
                <span class="absolute inset-0" aria-hidden="true"></span>
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "CardListCard",
    props: {
        link: {
            type: Boolean,
            default: false,
        },
        href: {
            type: String,
            default: "",
        },
        status: {
            type: String,
            default: "",
        },
    },
    computed: {
        classes() {
            let classes = [
                "relative rounded-lg border px-3 py-2 shadow flex items-center space-x-3 text-center",
            ];

            let hoverClasses = [
                "focus-within:ring-2 focus-within:ring-offset-2",
            ];

            if (this.status === "danger") {
                classes.push("border-red-300 bg-red-100");
                hoverClasses.push(
                    "hover:border-red-400 focus-within:ring-red-500 hover:bg-red-200"
                );
            } else if (this.status === "success") {
                classes.push("border-green-300 bg-green-100");
                hoverClasses.push(
                    "hover:border-green-400 focus-within:ring-green-500 hover:bg-green-200"
                );
            } else if (this.status === "warning") {
                classes.push("border-yellow-300 bg-yellow-100");
                hoverClasses.push(
                    "hover:border-yellow-400 focus-within:ring-yellow-500 hover:bg-yellow-200"
                );
            } else {
                classes.push("border-gray-300 bg-white");
                hoverClasses.push(
                    "hover:border-gray-300 focus-within:ring-indigo-500 hover:bg-gray-50"
                );
            }
            let classString = classes.join(" ");

            if (this.link) {
                classString += " " + hoverClasses.join(" ");
            }

            return classString;
        },
    },
};
</script>

<style scoped></style>
