<template>
    <span class="inline-flex rounded-md">
        <button
            id="user-menu-button"
            type="button"
            class="
                bg-gray-800
                text-gray-500
                hover:text-gray-200
                inline-flex
                items-center
                px-3
                py-2
                border border-transparent
                text-sm
                leading-4
                font-medium
                rounded-md
                focus:outline-none
                transition
            "
        >
            {{ $page.props.auth.user.name }}
            <Icon icon="chevron-down" classes="ml-2 -mr-0.5 h-4 w-4" />
        </button>
    </span>
</template>

<script>
import Icon from "@/Components/Icon";
export default {
    name: "ProfileButton",
    components: { Icon },
};
</script>
