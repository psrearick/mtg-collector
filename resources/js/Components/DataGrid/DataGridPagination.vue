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
                <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium">1</span>
                    to
                    <span class="font-medium">10</span>
                    of
                    <span class="font-medium">97</span>
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
                        class-additions="rounded-l-md"
                        :href="pagination.previous_page_url"
                    >
                        <span class="sr-only">Previous</span>
                        <icon icon="chevron-left" />
                    </data-grid-pagination-button>

                    <data-grid-pagination-button
                        v-for="(page, index) in pages"
                        :key="index"
                        :href="page.url"
                        :active="page.active"
                    >
                        {{ page.label }}
                    </data-grid-pagination-button>

                    <data-grid-pagination-button
                        >...</data-grid-pagination-button
                    >

                    <data-grid-pagination-button
                        :href="pagination.last_page_url"
                    >
                        {{ pagination.last_page }}
                    </data-grid-pagination-button>

                    <data-grid-pagination-button
                        class-additions="rounded-r-md"
                        :href="pagination.next_page_url"
                    >
                        <span class="sr-only">Next</span>
                        <icon icon="chevron-right" />
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

    computed: {
        pages: function () {
            let links = _.cloneDeep(this.pagination.links);
            links.shift();
            links.pop();
            links.pop();
            return _.slice(links, 0, this.pagination.pages);
            return [];
        },
    },
};
</script>

<style scoped></style>
