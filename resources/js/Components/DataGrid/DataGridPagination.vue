<template>
    <div
        class="
            bg-white
            px-4
            py-3
            flex
            items-center
            justify-between
            border-t border-gray-200
            sm:px-6
        "
    >
        <div class="flex-1 flex justify-between sm:hidden">
            <a
                :href="pagination.previous_page_url"
                class="
                    relative
                    inline-flex
                    items-center
                    px-4
                    py-2
                    border border-gray-300
                    text-sm
                    font-medium
                    rounded-md
                    text-gray-700
                    bg-white
                    hover:bg-gray-50
                "
            >
                Previous
            </a>
            <a
                :href="pagination.next_page_url"
                class="
                    ml-3
                    relative
                    inline-flex
                    items-center
                    px-4
                    py-2
                    border border-gray-300
                    text-sm
                    font-medium
                    rounded-md
                    text-gray-700
                    bg-white
                    hover:bg-gray-50
                "
            >
                Next
            </a>
        </div>
        <div
            class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between"
        >
            <div>
                <p v-if="pagination.total" class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium">{{ pagination.from }}</span>
                    to
                    <span class="font-medium">{{ pagination.to }}</span>
                    of
                    <span class="font-medium">{{ pagination.total }}</span>
                    results
                </p>
            </div>
            <div>
                <nav
                    class="
                        relative
                        z-0
                        inline-flex
                        rounded-md
                        shadow-sm
                        -space-x-px
                    "
                    aria-label="Pagination"
                >
                    <data-grid-pagination-button
                        v-for="(page, index) in pagination.links"
                        :key="index"
                        :href="page.url"
                        :active="page.active"
                    >
                        <span v-if="index === 0">
                            <icon icon="chevron-left" />
                        </span>
                        <span v-else-if="index === pagination.links.length - 1">
                            <icon icon="chevron-right" />
                        </span>
                        <span v-else>
                            {{ page.label }}
                        </span>
                    </data-grid-pagination-button>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
import DataGridPaginationButton from "@/Components/DataGrid/DataGridPaginationButton";
import Icon from "@/Components/Icon";

export default {
    name: "DataGridPagination",

    components: {
        Icon,
        DataGridPaginationButton,
    },

    props: {
        pagination: {
            type: Object,
            default: () => {},
        },
    },
};
</script>
