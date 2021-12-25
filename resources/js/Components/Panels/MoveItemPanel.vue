<template>
    <ui-panel
        :show="show"
        :form="true"
        :clear="false"
        :title="title"
        save-text="Move"
        @update:show="$emit('update:show', $event)"
        @close="closePanel"
        @save="save"
    >
        <p>
            <span>Where would you like to move </span>
            <span class="text-gray-500 text-sm py-4 font-bold">{{
                collection.name
            }}</span>
            <span>?</span>
        </p>
        <form>
            <ui-select-menu
                v-model:show="destinationMenuShow"
                v-model:selected="form.destination"
                label="Destination"
                name="destination"
                class="mb-4"
                :required="true"
                :options="collectionOptions"
            />
        </form>
    </ui-panel>
</template>

<script>
import UiPanel from "@/UI/UIPanel";
import UiInput from "@/UI/Form/UIInput";
import UiSelectMenu from "@/UI/Form/UISelectMenu";

export default {
    name: "MoveItemPanel",

    components: {
        UiSelectMenu,
        UiInput,
        UiPanel,
    },

    props: {
        show: {
            type: Boolean,
            default: false,
        },
        collection: {
            type: Object,
            default: () => {},
        },
        folder: {
            type: Object,
            default: () => {},
        },
    },

    emits: ["update:show", "close"],

    data() {
        return {
            collectionOptions: [],
            destinationMenuShow: false,
            form: {
                destination: null,
                id: null,
                type: null,
            },
            list: [],
            folders: [],
        };
    },

    computed: {
        title() {
            return (
                "Edit " +
                (this.collection.type === "collection"
                    ? "Collection"
                    : "Folder")
            );
        },
        thisFolder() {
            if (this.collection.type === "collection") {
                return {};
            }

            return this.folders.find(
                (folder) => folder.id === this.collection.id
            );
        },
    },

    watch: {
        show: function (value) {
            if (value) {
                this.form.id = this.collection.id;
                this.form.type = this.collection.type;
                this.getFolders();
                return;
            }
            this.clearForm();
        },
    },

    methods: {
        clearForm() {
            this.form = {
                id: null,
                destination: null,
                type: "",
            };
            this.folders = [];
            this.destinationMenuShow = false;
        },
        close() {
            this.$emit("close");
            this.$emit("update:show", false);
        },
        closePanel() {
            this.clearForm();
            this.close();
        },
        filterFolderForTopLevelOptions(folder) {
            if (folder.id === 0) {
                return false;
            }

            if (this.collection.type === "collection") {
                return true;
            }

            if (folder.id === this.collection.id) {
                return false;
            }

            const descendantIds = _.map(this.thisFolder.descendants, "id");

            return descendantIds.indexOf(folder.id) === -1;
        },
        filterFolderForNestedOptions(folder) {
            if (folder.id === this.folder.id) {
                return false;
            }

            if (this.collection.type === "collection") {
                return true;
            }

            if (folder.id === this.collection.id) {
                return false;
            }

            if (folder.id === this.folder.id) {
                return false;
            }

            const descendantIds = _.map(this.thisFolder.descendants, "id");

            return descendantIds.indexOf(folder.id) === -1;
        },
        getFolders() {
            axios
                .get("/collections/collections/folders-tree")
                .then((folders) => {
                    this.folders.push({
                        id: 0,
                        name: "Root",
                        ancestry: "",
                    });
                    folders.data.forEach((folder) => {
                        this.mapFolder(folder);
                    });

                    this.collectionOptions = this.folders
                        .map((folder) => {
                            return {
                                parent_id: folder.parent_id,
                                id: folder.id,
                                label:
                                    (folder.ancestry.length
                                        ? folder.ancestry + " > "
                                        : "") + folder.name,
                            };
                        })
                        .filter((folder) => {
                            if (!this.folder) {
                                return this.filterFolderForTopLevelOptions(
                                    folder
                                );
                            }
                            return this.filterFolderForNestedOptions(folder);
                        });
                });
        },
        mapFolder(folder) {
            folder.ancestry = "";
            if (folder.parent) {
                folder.ancestry =
                    (folder.parent.parent
                        ? folder.parent.ancestry + " > "
                        : "") + folder.parent.name;
            }
            folder.descendants = this.getFolderDescendants(folder);
            this.folders.push(folder);
            folder.children.forEach((child) => {
                child.parent = folder;
                this.mapFolder(child);
            });
        },
        getFolderDescendants(folder) {
            if (!folder.children) {
                return [folder];
            }
            let descendants = folder.children;
            folder.children.forEach((child) => {
                descendants = descendants.concat(
                    this.getFolderDescendants(child)
                );
            });
            return descendants;
        },
        save() {
            let self = this;
            this.$inertia.visit("/collections/collections/move", {
                method: "patch",
                data: this.form,
                preserveState: true,
                onSuccess: () => {
                    self.closePanel();
                },
            });
        },
    },
};
</script>
