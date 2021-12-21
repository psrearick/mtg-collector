<template>
    <div :class="classes">
        <div class="grid grid-cols-8">
            <div class="col-span-1">
                <slot name="left"></slot>
            </div>
            <div class="col-span-6">
                <inertia-link
                    v-if="hasLink"
                    :href="href"
                    class="focus:outline-none"
                >
                    <slot name="main"></slot>
                </inertia-link>
                <div v-else>
                    <span class="absolute inset-0" aria-hidden="true"></span>
                    <slot name="main"></slot>
                </div>
            </div>
            <div class="col-span-1 flex flex-col">
                <div v-if="menu">
                    <div>
                        <dropdown>
                            <template #trigger>
                                <Icon
                                    icon="dots-vertical"
                                    size="20px"
                                    class="
                                        float-right
                                        m-1
                                        hover:text-indigo-500
                                    "
                                />
                            </template>
                            <template #content>
                                <dropdown-link
                                    v-for="(menuItem, menuIndex) in menu"
                                    :key="menuIndex"
                                    :click="menuItem"
                                >
                                    {{ menuItem.content }}
                                </dropdown-link>
                            </template>
                        </dropdown>
                    </div>
                </div>
                <slot name="right"></slot>
            </div>
        </div>
    </div>
</template>

<script>
import Dropdown from "@/Components/Dropdown.vue";
import Icon from "@/Components/Icon.vue";
import DropdownLink from "@/Components/DropdownLink.vue";

export default {
    name: "CardListCardWithMenu",

    components: { Dropdown, Icon, DropdownLink },

    props: {
        href: {
            type: String,
            default: "",
        },
        status: {
            type: String,
            default: "",
        },
        menu: {
            type: Array,
            default: () => {},
        },
    },
    computed: {
        classes() {
            let classes = [
                "relative rounded-lg border px-3 py-2 shadow-md text-center",
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
                    "hover:border-gray-300 focus-within:ring-indigo-500 hover:bg-gray-100"
                );
            }
            let classString = classes.join(" ");

            if (this.hasLink) {
                classString += " " + hoverClasses.join(" ");
            }

            return classString;
        },
        hasLink() {
            return this.href.length > 0;
        },
    },
};
</script>
