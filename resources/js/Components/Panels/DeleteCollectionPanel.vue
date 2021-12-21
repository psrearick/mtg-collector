<template>
    <ui-panel
        :show="show"
        :form="true"
        :clear="false"
        title="Delete Collection"
        save-text="Delete"
        save-button-style="danger"
        @update:show="$emit('update:show', $event)"
        @close="closePanel"
        @save="save"
    >
        <p>
            Do you really want to delete the following collection? This will
            remove all cards in the collection as well.
        </p>
        <p class="text-gray-500 text-sm pt-6">{{ collection.name }}</p>
        <p class="text-gray-500 text-sm pt-1">{{ collection.description }}</p>
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

    computed: {
        saveUrl: function () {
            return "/collections/collections/" + this.collection.id;
        },
        saveMethod: function () {
            return "delete";
        },
    },

    watch: {
        errors: function (value) {
            this.errorMessages = value;
        },
    },

    methods: {
        close() {
            this.$emit("close");
            this.$emit("update:show", false);
        },
        closePanel() {
            this.close();
        },
        save() {
            let self = this;
            this.$inertia.visit(this.saveUrl, {
                method: this.saveMethod,
                preserveState: true,
                onSuccess: () => {
                    self.closePanel();
                },
            });
        },
    },
};
</script>
