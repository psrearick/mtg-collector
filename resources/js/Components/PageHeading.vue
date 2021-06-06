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
            <h1 class="text-3xl font-bold text-white">Dashboard</h1>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <button
                    type="button"
                    class="
                        inline-flex
                        items-center
                        px-4
                        py-2
                        border border-gray-300
                        rounded-md
                        shadow-sm
                        text-sm
                        font-medium
                        text-gray-700
                        bg-white
                        hover:bg-gray-50
                        focus:outline-none
                        focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                    "
                >
                    Edit
                </button>
                <button
                    type="button"
                    class="
                        ml-3
                        inline-flex
                        items-center
                        px-4
                        py-2
                        border border-transparent
                        rounded-md
                        shadow-sm
                        text-sm
                        font-medium
                        text-white
                        bg-indigo-600
                        hover:bg-indigo-700
                        focus:outline-none
                        focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                    "
                >
                    Publish
                </button>
            </div>
        </div>
    </header>
</template>

<script>
export default {
    name: "PageHeading",
};
</script>

<style scoped></style>
