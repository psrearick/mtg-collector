<template>
    <div
        class="
            min-h-screen
            flex flex-col
            sm:justify-center
            items-center
            pt-6
            sm:pt-0
            bg-gray-100
        "
    >
        <div>
            <inertia-link href="/">
                <Logo
                    classes="w-20 h-20 text-gray-500"
                    text-class="text-gray-500 text-xl"
                />
            </inertia-link>
        </div>

        <div
            class="
                w-full
                sm:max-w-md
                mt-6
                px-6
                py-4
                bg-white
                shadow-md
                overflow-hidden
                sm:rounded-lg
            "
        >
            <slot />
        </div>
    </div>
</template>

<script>
import Logo from "@/Components/Logo";

export default {
    title: "MTG Collector",

    components: {
        Logo,
    },
};
</script>
