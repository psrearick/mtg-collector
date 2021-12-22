<template>
    <ui-panel
        :show="show"
        :form="true"
        :clear="false"
        title="Remove Cards from Collection"
        save-text="Remove"
        save-button-style="danger"
        @update:show="$emit('update:show', $event)"
        @close="closePanel"
        @save="save"
    >
        <p>
            Do you really want to remove the following cards from this
            collection?
        </p>
        <div class="pt-6">
            <p>Collection</p>
            <p class="text-gray-500 text-sm pt-1">{{ collection.name }}</p>
            <p class="text-gray-500 text-sm pt-1">
                {{ collection.description }}
            </p>
        </div>
        <div class="pt-6">
            <p>Cards</p>
            <p
                v-for="(item, index) in form.items"
                :key="index"
                class="pt-1 text-gray-500 text-sm"
            >
                {{ getCardName(item) }}
            </p>
        </div>
    </ui-panel>
</template>

<script>
import UiPanel from "@/UI/UIPanel";
import UiButton from "@/UI/UIButton";
import UiTextArea from "@/UI/UITextArea";

export default {
    name: "RemoveFromCollectionPanel",

    components: {
        UiTextArea,
        UiButton,
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
        data: {
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
                collection: null,
                items: [],
            },
            errorMessages: {},
        };
    },

    computed: {
        saveUrl: function () {
            return "/collections/cards/remove-card";
        },
        saveMethod: function () {
            return "delete";
        },
    },

    watch: {
        errors: function (value) {
            this.errorMessages = value;
        },
        show: function (value) {
            if (value) {
                this.form.collection = this.collection.id;
                this.form.items = this.getItems();
                return;
            }
        },
    },

    methods: {
        close() {
            this.$emit("close");
            this.$emit("update:show", false);
        },
        getCardName(card) {
            let set = card.set || "";
            let finish = card.finish || "";
            let string = card.name;
            if (set.length > 0 || finish.length > 0) {
                string += " (";
                if (set.length > 0) {
                    string += set;
                }
                if (set.length > 0 && finish.length > 0) {
                    string += ", ";
                }
                if (finish.length > 0) {
                    string += finish;
                }
                string += ")";
            }

            return string;
        },
        closePanel() {
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
        getItems() {
            if (!this.data) {
                return [];
            }

            return this.data.data().filter((datum, key) => {
                return this.data.selectedItems().includes(key);
            });
        },
    },
};
</script>
