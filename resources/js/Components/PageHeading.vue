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
                <h1 v-if="headerText" class="text-3xl font-bold text-white">
                    {{ headerText }}
                </h1>
                <p v-if="subheaderText" class="text-white">
                    {{ subheaderText }}
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <slot name="header-right" />
                <component
                    :is="headerRightComponent.is"
                    v-if="headerRightComponent"
                    v-bind="headerRightComponent.props"
                ></component>
            </div>
        </div>
    </header>
</template>

<script>
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
    },
};
</script>
