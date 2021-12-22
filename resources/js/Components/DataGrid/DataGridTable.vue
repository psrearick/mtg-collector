<template>
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div v-if="hasSelectMenu" class="float-right sm:mx-6 lg:mx-8">
                <ui-dropdown
                    :label="selectMenuLabel"
                    :menu="selectMenuWithItems"
                    :active="selectedOptions.length > 0"
                />
            </div>
            <div
                class="
                    py-2
                    align-middle
                    inline-block
                    min-w-full
                    sm:px-6
                    lg:px-8
                "
            >
                <div
                    class="
                        shadow
                        overflow-hidden
                        border-b border-gray-200
                        sm:rounded-lg
                    "
                >
                    <table
                        :class="
                            classes.table
                                ? classes.table
                                : 'min-w-full divide-y divide-gray-200'
                        "
                    >
                        <thead class="bg-gray-50">
                            <tr
                                :class="
                                    classes.headerRow ? classes.headerRow : ''
                                "
                            >
                                <th v-if="hasSelectMenu" class="p-2 pl-4">
                                    <ui-checkbox
                                        v-model:value="selectAll"
                                        @update:value="updateSelectAll"
                                    />
                                </th>
                                <th
                                    v-for="(field, index) in topRowFields"
                                    :key="index"
                                    scope="col"
                                    :class="
                                        classes.headerCell
                                            ? classes.headerCell
                                            : 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'
                                    "
                                >
                                    <a
                                        href="#"
                                        @click.prevent="sortField(field.key)"
                                    >
                                        <span class="block">
                                            {{ field.label ? field.label : "" }}
                                        </span>
                                        <span class="block text-gray-400">
                                            {{
                                                field.subLabel
                                                    ? field.subLabel
                                                    : ""
                                            }}
                                        </span>
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody
                            v-for="(item, key) in data"
                            :key="key"
                            :class="
                                classes.tbody
                                    ? classes.tbody
                                    : 'bg-white border-b-2 border-gray-200 hover:bg-gray-50'
                            "
                        >
                            <tr
                                :class="
                                    classes.tableRow ? classes.tableRow : ''
                                "
                            >
                                <td v-if="hasSelectMenu" class="p-2 pl-4">
                                    <ui-checkbox
                                        :value="selectedOptions.includes(key)"
                                        @update:value="check(key)"
                                    />
                                </td>
                                <td
                                    v-for="(field, fieldKey) in topRowFields"
                                    :key="fieldKey"
                                    :class="
                                        classes.tableCell
                                            ? classes.tableCell
                                            : 'py-2 px-6'
                                    "
                                >
                                    <a
                                        v-if="field.link"
                                        class="
                                            text-blue-700
                                            hover:text-blue-900
                                        "
                                        href="#"
                                        @click.prevent="click(item, field)"
                                    >
                                        <data-grid-table-field
                                            :data="item"
                                            :field="field"
                                        />
                                    </a>
                                    <p v-else>
                                        <data-grid-table-field
                                            :data="item"
                                            :field="field"
                                        />
                                    </p>
                                </td>
                            </tr>
                            <tr
                                v-if="bottomRowFields.length"
                                :class="
                                    classes.tableRow ? classes.tableRow : ''
                                "
                            >
                                <td
                                    v-for="(field, fieldKey) in bottomRowFields"
                                    :key="fieldKey"
                                    :class="
                                        classes.tableCell
                                            ? classes.tableCell
                                            : 'py-2 px-6'
                                    "
                                >
                                    <a
                                        v-if="field.link"
                                        class="
                                            text-blue-700
                                            hover:text-blue-900
                                        "
                                        href="#"
                                        @click.prevent="click(item, field)"
                                    >
                                        <data-grid-table-field
                                            :data="item"
                                            :field="field"
                                        />
                                    </a>
                                    <p v-else>
                                        <data-grid-table-field
                                            :data="item"
                                            :field="field"
                                        />
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import DataGridTableField from "@/Components/DataGrid/DataGridTableField";
import UiCheckbox from "@/UI/Form/UICheckbox";
import UiDropdown from "@/UI/Dropdown/UIDropdown";

export default {
    name: "DataTable",

    components: { DataGridTableField, UiCheckbox, UiDropdown },

    props: {
        gridName: {
            type: String,
            default: "",
        },
        data: {
            type: Array,
            default: () => [],
        },
        fields: {
            type: Array,
            default: () => [],
        },
        selectMenu: {
            type: Array,
            default: () => [],
        },
        selected: {
            type: Array,
            default: () => [],
        },
        fieldRows: {
            type: Array,
            default: () => [],
        },
        sort: {
            type: Object,
            default: () => {},
        },
        classes: {
            type: Object,
            default: () => {
                return { table: null };
            },
        },
    },

    emits: ["update:sort"],

    data() {
        return {
            sorts: {},
            selectAll: false,
            selectedOptions: [],
            selectMenuWithItems: [],
        };
    },

    computed: {
        hasSelectMenu() {
            return this.selectMenu.length > 0;
        },
        selectMenuLabel() {
            return "Edit Selected (" + this.selectedOptions.length + ")";
        },
        topRowFields() {
            if (this.fields) {
                return this.filterFields(this.fields);
            }
            if (this.fieldRows) {
                const topRow = this.fieldRows.filter((row) => {
                    return row.row === 1;
                });
                if (topRow.length) {
                    return this.filterFields(topRow[0].fields);
                }
            }
            return [];
        },
        bottomRowFields() {
            if (this.fieldRows) {
                const bottomRow = this.fieldRows.filter((row) => {
                    return row.row === 2;
                });
                if (bottomRow.length) {
                    return this.filterFields(bottomRow[0].fields);
                }
            }
            return [];
        },
    },

    mounted() {
        this.selectedOptions = _.clone(this.selected);
        this.setMenu();
    },

    created() {
        this.emitter.on(
            "clear_data_grid_selections",
            this.clearDataGridSelections
        );
    },

    methods: {
        clearDataGridSelections(value) {
            if (value === this.gridName) {
                this.updateSelectAll();
            }
        },
        filterFields(fields) {
            return fields.filter((field) => {
                return field.visible;
            });
        },
        sortField(field) {
            if (!(field in this.sorts)) {
                this.sorts[field] = "ASC";
                return;
            }

            let currentField = this.sorts[field];
            if (currentField === "DESC") {
                delete this.sorts[field];
                return;
            }

            this.sorts[field] = "DESC";
            this.$emit("update:sort", this.sorts);
        },
        click(item, field) {
            this.emitter.emit(field.events.click, item);
        },
        updateSelectAll(value) {
            if (!value) {
                this.selectedOptions = [];
                this.selectAll = false;
                return;
            }

            this.data.forEach((el, index) => {
                if (!this.isChecked(index)) {
                    this.check(index);
                }
            });
        },
        isChecked(key) {
            return this.selectedOptions.includes(key);
        },
        check(key) {
            const index = this.selectedOptions.indexOf(key);
            if (index > -1) {
                this.selectedOptions.splice(index, 1);
                return;
            }

            this.selectedOptions.push(key);
        },
        getData() {
            return this.data;
        },
        getSelectedOptions() {
            return this.selectedOptions;
        },
        setMenu() {
            if (!this.hasSelectMenu) {
                return;
            }

            this.selectMenuWithItems = this.selectMenu.map((item) => {
                item["selectedItems"] = this.getSelectedOptions;
                item["data"] = this.getData;
                return item;
            });
        },
    },
};
</script>
