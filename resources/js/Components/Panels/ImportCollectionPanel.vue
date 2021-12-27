<template>
    <ui-panel
        :show="show"
        :form="true"
        :clear="false"
        :title="title"
        save-text="Import"
        :disable-save="processing"
        @update:show="$emit('update:show', $event)"
        @close="closePanel"
        @save="save"
    >
        <div class="text-gray-500 text-sm py-4">
            <p>Import cards into the following collection:</p>
            <p class="mt-4 font-bold">{{ collection.name }}</p>
            <p class="mt-4">
                This will add cards to the collection without removing the
                existing cards.
            </p>
        </div>
        <p class="mt-8">How would you like to import the cards?</p>
        <form>
            <ui-select-menu
                v-model:show="importTypeShowMenu"
                v-model:selected="typeVal"
                label="Import Type"
                name="import-type"
                class="my-4"
                :required="true"
                :options="typeOptions"
            />
            <ui-file-upload
                v-if="form.type === 'csv' || form.type === 'txt'"
                v-model="form.file"
                name="file"
                label="Collection File"
                :types="typeOptions[typeVal]['label']"
            />
            <ui-text-area
                v-if="form.type === 'pasted'"
                v-model="form.pasted"
                name="collection-text"
                label="Collection Text"
                placeholder="Paste collection list here"
            />
        </form>
    </ui-panel>
</template>
<script>
import UiPanel from "@/UI/UIPanel";
import UiFileUpload from "@/UI/Form/UIFileUpload";
import UiSelectMenu from "@/UI/Form/UISelectMenu";
import UiTextArea from "@/UI/Form/UITextArea";

export default {
    name: "ImportCollectionPanel",

    components: {
        UiSelectMenu,
        UiFileUpload,
        UiPanel,
        UiTextArea,
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
    },

    emits: ["update:show", "close"],

    data() {
        return {
            form: {
                file: null,
                type: "",
                pasted: "",
            },
            typeVal: null,
            importTypeShowMenu: false,
            title: "Import into Collection",
            processing: false,
            typeOptions: [
                {
                    id: 0,
                    label: "CSV",
                    value: "csv",
                },
                {
                    id: 1,
                    label: "TXT",
                    value: "txt",
                },
                {
                    id: 2,
                    label: "Pasted Text",
                    value: "pasted",
                },
            ],
        };
    },

    watch: {
        show() {
            this.clearForm();
        },
        typeVal(value) {
            this.form.type = this.typeOptions[value]["value"];
            this.form.file = null;
            this.form.pasted = null;
        },
    },

    methods: {
        clearForm() {
            this.form = {
                file: null,
                type: "",
                pasted: "",
            };
            this.importTypeShowMenu = false;
            this.processing = false;
        },
        close() {
            this.$emit("close");
            this.$emit("update:show", false);
        },
        closePanel() {
            this.clearForm();
            this.close();
        },
        save() {
            let self = this;
            this.processing = true;
            this.$inertia.visit("/collections/collections/import", {
                method: "post",
                data: {
                    form: this.form,
                    collection: this.collection.id,
                },
                preserveState: true,
                onSuccess: () => {
                    this.processing = false;
                    self.closePanel();
                },
            });
        },
    },
};
</script>
