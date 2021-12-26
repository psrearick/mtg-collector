<template>
    <div class="min-h-screen bg-gray-100">
        <div class="bg-gray-800 pb-32">
            <Navbar v-if="$page.props.auth.user" />
            <div v-else class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
                <div class="relative flex items-center justify-between h-16">
                    <div class="flex items-center px-2 lg:px-0">
                        <inertia-link href="/">
                            <Logo
                                class="text-gray-300 fill-current"
                                :show-text="true"
                                text-location="right"
                            />
                        </inertia-link>
                    </div>
                </div>
            </div>
            <PageHeading />
        </div>
        <main class="-mt-32">
            <div class="max-w-7xl mx-auto pb-12 md:px-6 lg:px-8">
                <div class="bg-white md:rounded-lg shadow px-2 py-6 sm:px-6">
                    <slot></slot>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
import Navbar from "@/Components/Navbar";
import Logo from "@/Components/Logo";
import PageHeading from "@/Components/PageHeading";

export default {
    name: "Public",

    title: "MTG Collector",

    components: {
        Navbar,
        PageHeading,
        Logo,
    },
};
</script>
