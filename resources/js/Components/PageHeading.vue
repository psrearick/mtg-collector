<template>
    <header class="py-10">
        <div
            class="
                max-w-7xl
                mx-auto
                px-4
                sm:px-6
                lg:px-8
                md:flex
                md:items-center
                md:justify-between
            "
        >
            <div>
                <h1
                    v-if="headerText"
                    class="text-3xl font-bold text-white"
                    v-html="headerText"
                />
                <p
                    v-if="subheaderText"
                    class="text-white"
                    v-html="subheaderText"
                />
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <slot name="header-right" />
                <component
                    :is="component"
                    v-if="headerRightComponent"
                    v-bind="headerRightComponent.props"
                ></component>
            </div>
        </div>
    </header>
</template>

<script>
import CollectionIndexHeaderRight from "@/Pages/Collections/Partials/CollectionIndexHeaderRight";
import CollectionShowHeaderRight from "@/Pages/Collections/Partials/CollectionShowHeaderRight";
import PrimaryButton from "@/Components/Buttons/PrimaryButton";
import UiButton from "@/UI/UIButton";

const componentMap = {
    CollectionIndexHeaderRight: CollectionIndexHeaderRight,
    CollectionShowHeaderRight: CollectionShowHeaderRight,
    PrimaryButton: PrimaryButton,
    UiButton: UiButton,
};

export default {
    name: "PageHeading",

    computed: {
        headerText() {
            return this.$store.getters.header;
        },
        subheaderText() {
            return this.$store.getters.subheader;
        },
        headerRightComponent() {
            return this.$store.getters.headerRightComponent;
        },
        component() {
            return componentMap[this.headerRightComponent.is];
        },
    },
};
</script>
