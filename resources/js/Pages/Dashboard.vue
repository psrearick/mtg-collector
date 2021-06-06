<template>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    You're logged in!
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Layout from "@/Layouts/Authenticated";

export default {
    components: {
        Layout,
    },

    layout: Layout,

    props: {
        auth: { type: Object, default: () => {} },
        errors: { type: Object, default: () => {} },
    },
};
</script>
