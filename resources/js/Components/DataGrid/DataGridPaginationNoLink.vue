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
            <p
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
                @click="toPage(pagination_local.current_page--)"
            >
                Previous
            </p>
            <p
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
                @click="toPage(pagination_local.current_page++)"
            >
                Next
            </p>
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
                        href="#"
                        :active="page.active"
                        @click="toPage(page.label)"
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
    name: "DataGridPaginationNoLink",

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

    emits: ["update:pagination"],

    data() {
        return {
            pagination_local: {
                current_page: null,
                from: null,
                last_page: null,
                per_page: 15,
                to: null,
                total: null,
            },
        };
    },

    watch: {
        pagination: {
            deep: true,
            handler() {
                this.pagination_local = this.pagination;
            },
        },
    },

    methods: {
        toPage(page) {
            const isNext = page.indexOf("Next") > -1;
            const isPrevious = page.indexOf("Previous") > -1;
            let nextLocation = page;
            if (isNext) {
                nextLocation = this.pagination.current_page + 1;
                if (nextLocation > this.pagination.last_page) {
                    return;
                }
            }
            if (isPrevious) {
                nextLocation = this.pagination.current_page - 1;
                if (nextLocation < 1) {
                    return;
                }
            }

            this.pagination_local.current_page = parseInt(nextLocation);
            this.$emit("update:pagination", this.pagination_local);
        },
    },
};
</script>
