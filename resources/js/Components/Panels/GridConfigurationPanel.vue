<template>
    <ui-panel
        :show="show"
        :form="true"
        :clear="false"
        title="Configure Table"
        save-text="Save"
        @update:show="$emit('update:show', $event)"
        @close="closePanel"
        @save="save"
    >
        <p class="text-gray-500 text-sm my-4 font-bold">Sort Fields</p>
        <div>
            <div
                v-for="(field, index) in sortableFields"
                :key="index"
                class="border border-1 rounded p-2 mb-2 shadow grid grid-cols-2"
            >
                <span class="whitespace-nowrap">
                    {{ field.label }}
                </span>
                <div class="grid grid-cols-3">
                    <span @click="updateSort(field)">
                        <Icon
                            v-if="field.sortDirection"
                            :icon="'sort-' + field.sortDirection"
                            classes="inline hover:text-gray-500"
                            size="1rem"
                            class="inline"
                        />
                        <Icon
                            v-else
                            icon="circle-x"
                            classes="inline hover:text-gray-500"
                            size="1rem"
                            class="inline"
                        />
                    </span>
                    <span>
                        <Icon
                            v-if="field.sortOrder > 0"
                            icon="arrow-narrow-up"
                            classes="inline hover:text-gray-500"
                            size="1rem"
                            class="inline"
                            @click="moveUp(field)"
                        />
                    </span>
                    <span>
                        <Icon
                            v-if="field.sortOrder < sortableFields.length - 1"
                            icon="arrow-narrow-down"
                            classes="inline hover:text-gray-500"
                            size="1rem"
                            class="inline"
                            @click="moveDown(field)"
                        />
                    </span>
                </div>
            </div>
        </div>
        <p
            v-if="filterableFields.length > 0"
            class="text-gray-500 text-sm mt-8 mb-4 font-bold"
        >
            Filter Fields
        </p>
        <div>
            <form>
                <div v-for="(field, index) in filterableFields" :key="index">
                    <component
                        :is="field.uiComponent"
                        v-model="form[field.key]"
                        :field="field"
                    />
                </div>
            </form>
        </div>
    </ui-panel>
</template>

<script>
import Icon from "@/Components/Icon";
import UiButton from "@/UI/UIButton";
import UiInput from "@/UI/Form/UIInput";
import UiMinMax from "@/UI/Form/UIMinMax";
import UiPanel from "@/UI/UIPanel";
import UiTextArea from "@/UI/Form/UITextArea";

export default {
    name: "GridConfigurationPanel",

    components: {
        Icon,
        UiButton,
        UiInput,
        UiMinMax,
        UiPanel,
        UiTextArea,
    },

    props: {
        errors: {
            type: Object,
            default: () => {},
        },
        fields: {
            type: Array,
            default: () => [],
        },
        gridName: {
            type: String,
            default: "",
        },
        show: {
            type: Boolean,
            default: false,
        },
    },

    emits: ["update:show", "close"],

    data() {
        return {
            form: {},
            errorMessages: {},
            sortFields: {},
            sortOrder: {},
        };
    },

    computed: {
        filterableFields() {
            return this.fields.filter((field) => {
                return field.filterable;
            });
        },
        saveMethod() {
            return "patch";
        },
        sortableFields() {
            let fields = this.fields
                .filter((field) => {
                    return field.sortable && field.visible;
                })
                .map((field) => {
                    field.sortDirection = null;
                    if (this.sortFields) {
                        if (field.key in this.sortFields) {
                            field.sortDirection = this.sortFields[field.key];
                        }
                    }

                    field.sortOrder = this.sortOrder[field.key];

                    return field;
                });

            return _.sortBy(fields, (field) => {
                return field.sortOrder;
            });
        },
    },

    watch: {
        errors: function (value) {
            this.errorMessages = value;
        },
        show: function (value) {
            if (value) {
                this.sortFields = this.getCurrentSortFields();
                this.sortOrder = this.getCurrentSortOrder();
                this.form = this.getCurrentFilters();
                return;
            }
            this.clearForm();
        },
    },

    methods: {
        clearForm() {
            // this.form = {};
        },
        getCurrentFilters() {
            let filters = this.$store.getters.filters;
            if (filters) {
                return _.cloneDeep(filters[this.gridName]);
            }

            return {};
        },
        getCurrentSortFields() {
            let fields = this.$store.getters.sortFields;
            if (fields) {
                return _.cloneDeep(fields[this.gridName]);
            }

            return {};
        },
        getCurrentSortOrder() {
            const storeOrder = _.cloneDeep(this.$store.getters.sortOrder) || {};
            let order = {};
            if (this.gridName in storeOrder) {
                order = storeOrder[this.gridName];
            }

            let fields = _.map(
                this.fields.filter((field) => {
                    return field.sortable && field.visible;
                }),
                "key"
            );

            fields.sort((a, b) => {
                let aPos = a in order ? order[a] : -1;
                let bPos = b in order ? order[b] : -1;

                if (aPos === bPos) {
                    return 0;
                }

                if (aPos === -1) {
                    return 1;
                }

                if (bPos === -1) {
                    return -1;
                }

                return bPos < aPos ? 1 : -1;
            });

            let fieldsOrdered = {};
            fields.forEach((field, index) => {
                fieldsOrdered[field] = index;
            });

            return fieldsOrdered;
        },
        close() {
            this.$emit("close");
            this.$emit("update:show", false);
        },
        closePanel() {
            this.clearForm();
            this.close();
        },
        updateSort(field) {
            let fieldname = field["key"];
            let direction = "asc";

            if (fieldname in this.sortFields) {
                direction = "desc";
                if (this.sortFields[fieldname] === "desc") {
                    delete this.sortFields[fieldname];
                    direction = null;
                }
            }

            if (direction) {
                this.sortFields[fieldname] = direction;
            }
        },
        moveUp(field) {
            let currentPosition = field.sortOrder;
            let newPosition = currentPosition - 1;

            let changed = [];
            Object.keys(this.sortOrder).forEach((key) => {
                if (
                    this.sortOrder[key] === currentPosition &&
                    changed.indexOf(key) === -1
                ) {
                    this.sortOrder[key] = newPosition;
                    changed.push(key);
                    return;
                }
                if (
                    this.sortOrder[key] === newPosition &&
                    changed.indexOf(key) === -1
                ) {
                    this.sortOrder[key] = currentPosition;
                    changed.push(key);
                    return;
                }
            });
        },
        moveDown(field) {
            let currentPosition = field.sortOrder;
            let newPosition = currentPosition + 1;

            let changed = [];
            Object.keys(this.sortOrder).forEach((key) => {
                if (
                    this.sortOrder[key] === currentPosition &&
                    changed.indexOf(key) === -1
                ) {
                    this.sortOrder[key] = newPosition;
                    changed.push(key);
                    return;
                }
                if (
                    this.sortOrder[key] === newPosition &&
                    changed.indexOf(key) === -1
                ) {
                    this.sortOrder[key] = currentPosition;
                    changed.push(key);
                    return;
                }
            });
        },
        save() {
            let self = this;
            this.$store.dispatch("setSortOrder", {
                order: this.sortOrder,
                gridName: this.gridName,
            });
            this.$store.dispatch("setSortFields", {
                fields: this.sortFields,
                gridName: this.gridName,
            });
            this.$store.dispatch("setFilters", {
                filters: this.form,
                gridName: this.gridName,
            });
            this.emitter.emit("sort", this.gridName);
            self.closePanel();
        },
    },
};
</script>
