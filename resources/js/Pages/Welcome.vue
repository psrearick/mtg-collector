<template>
    <div
        class="
            relative
            min-h-screen
            bg-gray-100
            dark:bg-gray-900
            sm:items-center
            sm:pt-0
        "
    >
        <div class="w-full text-center pt-32 px-16 pb-16">
            <Icon
                icon="collection"
                size="14rem"
                classes="text-gray-600 inline-block"
            />
            <h1 class="text-4xl mt-4 text-gray-600">MTG Collector</h1>
        </div>

        <div v-if="canLogin" class="px-6 py-4 block">
            <inertia-link
                v-if="$page.props.auth.user"
                href="/dashboard"
                class="text-sm text-gray-700 underline"
            >
                Dashboard
            </inertia-link>

            <div v-else class="flex justify-around max-w-sm mx-auto">
                <primary-button :href="route('login')"> Log in </primary-button>
                <primary-button v-if="canRegister" :href="route('register')">
                    Register
                </primary-button>
            </div>
        </div>
    </div>
</template>

<script>
import Layout from "@/Layouts/Layout";
import Icon from "@/Components/Icon";
import PrimaryButton from "@/Components/Buttons/PrimaryButton";

export default {
    components: { Icon, PrimaryButton },
    layout: Layout,
    props: {
        auth: { type: Object, default: () => {} },
        canLogin: Boolean,
        canRegister: Boolean,
        errors: { type: Object, default: () => {} },
        laravelVersion: { type: String, default: "" },
        phpVersion: { type: String, default: "" },
    },
};
</script>

<style scoped>
.bg-gray-100 {
    background-color: #f7fafc;
    background-color: rgba(247, 250, 252, var(--tw-bg-opacity));
}

.border-gray-200 {
    border-color: #edf2f7;
    border-color: rgba(237, 242, 247, var(--tw-border-opacity));
}

.text-gray-400 {
    color: #cbd5e0;
    color: rgba(203, 213, 224, var(--tw-text-opacity));
}

.text-gray-500 {
    color: #a0aec0;
    color: rgba(160, 174, 192, var(--tw-text-opacity));
}

.text-gray-600 {
    color: #718096;
    color: rgba(113, 128, 150, var(--tw-text-opacity));
}

.text-gray-700 {
    color: #4a5568;
    color: rgba(74, 85, 104, var(--tw-text-opacity));
}

.text-gray-900 {
    color: #1a202c;
    color: rgba(26, 32, 44, var(--tw-text-opacity));
}

@media (prefers-color-scheme: dark) {
    .dark\:bg-gray-800 {
        background-color: #2d3748;
        background-color: rgba(45, 55, 72, var(--tw-bg-opacity));
    }

    .dark\:bg-gray-900 {
        background-color: #1a202c;
        background-color: rgba(26, 32, 44, var(--tw-bg-opacity));
    }

    .dark\:border-gray-700 {
        border-color: #4a5568;
        border-color: rgba(74, 85, 104, var(--tw-border-opacity));
    }

    .dark\:text-white {
        color: #fff;
        color: rgba(255, 255, 255, var(--tw-text-opacity));
    }

    .dark\:text-gray-400 {
        color: #cbd5e0;
        color: rgba(203, 213, 224, var(--tw-text-opacity));
    }
}
</style>
