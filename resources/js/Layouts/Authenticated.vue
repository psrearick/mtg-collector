<template>
    <div class="min-h-screen bg-gray-100">
        <div class="bg-gray-800 pb-32">
            <Navbar />
            <PageHeading />
        </div>
        <main class="-mt-32">
            <div class="max-w-7xl mx-auto pb-12 px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow px-5 py-6 sm:px-6">
                    <slot></slot>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
import Navbar from "@/Components/Navbar";
import PageHeading from "@/Components/PageHeading";

export default {
    name: "Authenticated",

    title: "MTG Collector",

    components: {
        Navbar,
        PageHeading,
    },
};
</script>
