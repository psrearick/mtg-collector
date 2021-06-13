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
            <h1 class="text-3xl font-bold text-white">{{ text }}</h1>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <slot name="header-right" />
            </div>
        </div>
    </header>
</template>

<script>
export default {
    name: "PageHeading",

    props: {
        text: {
            type: String,
            default: "",
        },
    },
};
</script>
