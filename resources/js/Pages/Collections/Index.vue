<template>
    <div>
        <div v-if="collections.data.length">
            <card-list>
                <card-list-card-with-menu
                    v-for="(collection, index) in collections.data"
                    :key="index"
                    :href="
                        route('collections.show', { collection: collection.id })
                    "
                    :menu="getMenu(index)"
                >
                    <template #main>
                        <div class="py-4">
                            {{ collection.name }}
                        </div>
                        <div class="grid grid-cols-2 pb-4">
                            <div>
                                <p class="text-xs text-gray-500">Cards</p>
                                <p>{{ collection.count }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Value</p>
                                <p>{{ format(collection.value) }}</p>
                            </div>
                        </div>
                    </template>
                </card-list-card-with-menu>
            </card-list>
        </div>
        <div v-else>
            <p>You do not have any collections. Please create one.</p>
        </div>
        <edit-collection-panel
            v-model:show="showEditCollectionPanel"
            :collection="editCollection"
        />
        <delete-collection-panel
            v-model:show="showDeleteCollectionPanel"
            :collection="editCollection"
        />
    </div>
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import { formatCurrency } from "@/Shared/api/ConvertValue";
import CardList from "@/Components/CardLists/CardList.vue";
import CardListCardWithMenu from "@/Components/CardLists/CardListCardWithMenu.vue";
import EditCollectionPanel from "@/Components/Panels/EditCollectionPanel.vue";
import DeleteCollectionPanel from "@/Components/Panels/DeleteCollectionPanel.vue";

export default {
    name: "Index",

    components: {
        CardList,
        CardListCardWithMenu,
        EditCollectionPanel,
        DeleteCollectionPanel,
    },

    layout: Layout,

    title: "MTG Collector - Collection Index",

    header: "Collections",

    props: {
        collections: {
            type: Object,
            default: () => {},
        },
    },

    data() {
        return {
            showEditCollectionPanel: false,
            showDeleteCollectionPanel: false,
            editCollection: {},
        };
    },

    mounted() {
        this.$store.dispatch("updateHeaderRightComponent", {
            component: {
                is: "PrimaryButton",
                props: {
                    text: "Create Collection",
                    href: route("collections.create"),
                },
            },
        });
    },

    created() {
        this.emitter.on("dropdown_link_click", (clickData) => {
            this.linkClicked(clickData);
        });
    },

    methods: {
        format(value) {
            return value ? formatCurrency(value) : "N/A";
        },
        linkClicked(clickData) {
            this.editCollection = clickData.collection;
            if (clickData.action === "edit") {
                this.showEditCollectionPanel = true;
                return;
            }

            this.showDeleteCollectionPanel = true;
        },
        getMenu(index) {
            return [
                {
                    content: "Edit",
                    action: "edit",
                    collection: this.collections.data[index],
                },
                {
                    content: "Delete",
                    action: "delete",
                    collection: this.collections.data[index],
                },
            ];
        },
    },
};
</script>
