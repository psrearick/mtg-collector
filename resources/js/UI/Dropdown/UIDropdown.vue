<template>
    <div>
        <ui-dropdown-menu :active="active">
            <template #trigger>
                <ui-dropdown-button :label="label" :active="active" />
            </template>
            <template #content>
                <ui-dropdown-link
                    v-for="(menuItem, menuIndex) in menu"
                    :key="menuIndex"
                    :click="menuItem"
                >
                    {{ menuItem.content }}
                </ui-dropdown-link>
            </template>
        </ui-dropdown-menu>
    </div>
</template>

<script>
import UiDropdownButton from "@/UI/Dropdown/UIDropdownButton";
import UiDropdownMenu from "@/UI/Dropdown/UIDropdownMenu";
import UiDropdownLink from "@/UI/Dropdown/UIDropdownLink";

export default {
    name: "UiDropdown",

    components: { UiDropdownButton, UiDropdownMenu, UiDropdownLink },

    props: {
        active: {
            type: Boolean,
            default: true,
        },
        label: {
            type: String,
            default: "Options",
        },
        menu: {
            type: Array,
            default: () => [],
        },
    },

    created() {
        this.emitter.on("dropdown_link_click", (clickData) => {
            this.emitter.emit(clickData.action, clickData);
        });
    },
};
</script>
