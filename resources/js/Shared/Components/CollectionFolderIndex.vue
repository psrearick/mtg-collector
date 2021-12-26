<template>
    <div>
        <div v-if="folders.length || hasParentFolder" class="mb-12">
            <card-list>
                <card-list-card-with-menu
                    v-if="hasParentFolder"
                    :href="parentFolderHref"
                    class="bg-secondary-50"
                    grid-classes="h-full"
                    main-classes="my-auto"
                >
                    <template #main>
                        <Icon
                            icon="arrow-narrow-up"
                            classes="mx-auto my-4"
                            size="4em"
                        />
                    </template>
                </card-list-card-with-menu>
                <card-list-card-with-menu
                    v-for="(folderItem, index) in folders"
                    :key="index"
                    :href="
                        route('collection-folder.show', {
                            folder: folderItem.id,
                        })
                    "
                    :menu="getMenu(index, 'folder')"
                    class="bg-primary-50"
                >
                    <template #left>
                        <Icon icon="folder" />
                    </template>
                    <template #main>
                        <div class="py-4">
                            {{ folderItem.name }}
                        </div>
                        <div class="grid grid-cols-2 pb-4">
                            <div>
                                <p class="text-xs text-gray-500">Cards</p>
                                <p>{{ folderItem.count }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Value</p>
                                <p>{{ format(folderItem.value) }}</p>
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
                    <template v-if="collection.is_public" #left>
                        <span class="text-sm text-primary-500">Public</span>
                    </template>
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
        <div v-if="isEmpty">
            <p>You have no collections.</p>
        </div>
        <edit-collection-panel
            v-model:show="showEditCollectionPanel"
            :collection="editCollection"
        />
        <delete-collection-panel
            v-model:show="showDeleteCollectionPanel"
            :collection="editCollection"
        />
        <move-item-panel
            v-model:show="showMovePanel"
            :collection="editCollection"
            :folder="folder"
        />
        <public-link-modal
            v-model:show="showPublicLinkModel"
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
import PublicLinkModal from "@/Components/Modals/PublicLinkModal.vue";
import MoveItemPanel from "@/Components/Panels/MoveItemPanel";
import Icon from "@/Components/Icon";

export default {
    name: "CollectionFolderIndex",

    components: {
        CardList,
        CardListCardWithMenu,
        EditCollectionPanel,
        DeleteCollectionPanel,
        PublicLinkModal,
        Icon,
        MoveItemPanel,
    },

    props: {
        collections: {
            type: Array,
            default: () => [],
        },
        folder: {
            type: Object,
            default: () => {},
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
            showMovePanel: false,
            showPublicLinkModel: false,
            editCollection: {},
        };
    },

    computed: {
        hasParentFolder() {
            return this.folder;
        },
        parentFolderHref() {
            if (!this.folder) {
                return "";
            }

            if (!this.folder.parent_id) {
                return route("collections.index");
            }

            return route("collection-folder.show", {
                folder: this.folder.parent_id,
            });
        },
        isEmpty() {
            return this.folders.length === 0 && this.collections.length === 0;
        },
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

            if (clickData.action === "move") {
                this.showMovePanel = true;
                return;
            }

            if (clickData.action === "getLink") {
                this.showPublicLinkModel = true;
                return;
            }
        },
        getMenu(index, type) {
            const menus = [
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
                {
                    content: "Move",
                    action: "move",
                    collection:
                        type === "collection"
                            ? this.collections[index]
                            : this.folders[index],
                },
                {
                    content: "Get Public Link",
                    action: "getLink",
                    collection: this.collections[index],
                    restriction: "collection",
                },
            ];
            return menus.filter((menu) => {
                return !menu.restriction || menu.restriction === type;
            });
        },
    },
};
</script>
