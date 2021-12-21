<template>
    <ui-panel
        :show="show"
        :form="true"
        :clear="false"
        title="Edit Collection"
        save-text="Save"
        @update:show="$emit('update:show', $event)"
        @close="closePanel"
        @save="save"
    >
        <p class="text-gray-500 text-sm py-4">{{ collection.name }}</p>
        <form>
            <ui-input
                v-model="form.name"
                name="name"
                type="string"
                label="Name"
                :required="true"
                :error-message="errorMessages.name"
                class="mb-4"
            />
            <ui-text-area
                v-model="form.description"
                name="description"
                type="textarea"
                label="Description"
                :required="false"
                :error-message="errorMessages.description"
                class="mb-4"
            />
        </form>
    </ui-panel>
</template>

<script>
import UiPanel from "@/UI/UIPanel";
import UiInput from "@/UI/UIInput";
import UiButton from "@/UI/UIButton";
import UiTextArea from "@/UI/UITextArea";

export default {
    name: "EditCollectionPanel",

    components: {
        UiTextArea,
        UiButton,
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
        errors: {
            type: Object,
            default: () => {},
        },
    },

    emits: ["update:show", "close"],

    data() {
        return {
            form: {
                name: "",
                description: "",
                id: null,
            },
            errorMessages: {},
        };
    },

    computed: {
        saveUrl: function () {
            return "/collections/collections/" + this.collection.id;
        },
        saveMethod: function () {
            return "patch";
        },
    },

    watch: {
        errors: function (value) {
            this.errorMessages = value;
        },
        show: function (value) {
            if (value) {
                this.form.name = this.collection.name;
                this.form.description = this.collection.description;
                this.form.id = this.collection.id;
                return;
            }
            this.clearForm();
        },
    },

    methods: {
        clearForm() {
            this.form = {
                name: "",
                description: "",
                id: null,
            };
            this.errorMessages = {};
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
            this.$inertia.visit(this.saveUrl, {
                method: this.saveMethod,
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
