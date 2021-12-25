<template>
    <div>
        <div v-if="folders.length" class="mb-12">
            <card-list>
                <card-list-card-with-menu
                    v-for="(folder, index) in folders"
                    :key="index"
                    :href="
                        route('collection-folder.show', { folder: folder.id })
                    "
                    :menu="getMenu(index, 'folder')"
                    class="bg-primary-50"
                >
                    <template #left>
                        <Icon icon="folder" />
                    </template>
                    <template #main>
                        <div class="py-4">
                            {{ folder.name }}
                        </div>
                        <div class="grid grid-cols-2 pb-4">
                            <div>
                                <p class="text-xs text-gray-500">Cards</p>
                                <p>{{ folder.count }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Value</p>
                                <p>{{ format(folder.value) }}</p>
                            </div>
                        </div>
                    </template>
                </card-list-card-with-menu>
            </card-list>
        </div>
        <div v-if="collections.length">
            <card-list>
                <card-list-card-with-menu
                    v-for="(collection, index) in collections"
                    :key="index"
                    :href="
                        route('collections.show', { collection: collection.id })
                    "
                    :menu="getMenu(index, 'collection')"
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
import { formatCurrency } from "@/Shared/api/ConvertValue";
import CardList from "@/Components/CardLists/CardList";
import CardListCardWithMenu from "@/Components/CardLists/CardListCardWithMenu";
import EditCollectionPanel from "@/Components/Panels/EditCollectionPanel";
import DeleteCollectionPanel from "@/Components/Panels/DeleteCollectionPanel";
import Icon from "@/Components/Icon";

export default {
    name: "CollectionFolderIndex",

    components: {
        CardList,
        CardListCardWithMenu,
        EditCollectionPanel,
        DeleteCollectionPanel,
        Icon,
    },

    props: {
        collections: {
            type: Array,
            default: () => [],
        },
        folders: {
            type: Array,
            default: () => [],
        },
    },

    data() {
        return {
            showEditCollectionPanel: false,
            showDeleteCollectionPanel: false,
            editCollection: {},
        };
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

            if (clickData.action === "delete") {
                this.showDeleteCollectionPanel = true;
                return;
            }
        },
        getMenu(index, type) {
            return [
                {
                    content: "Edit",
                    action: "edit",
                    collection:
                        type === "collection"
                            ? this.collections[index]
                            : this.folders[index],
                },
                {
                    content: "Delete",
                    action: "delete",
                    collection:
                        type === "collection"
                            ? this.collections[index]
                            : this.folders[index],
                },
            ];
        },
    },
};
</script>
