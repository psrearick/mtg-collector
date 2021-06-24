<template>
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
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
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    v-for="(field, index) in topRowFields"
                                    :key="index"
                                    scope="col"
                                    class="
                                        px-6
                                        py-3
                                        text-left text-xs
                                        font-medium
                                        text-gray-500
                                        uppercase
                                        tracking-wider
                                    "
                                >
                                    <a
                                        href="#"
                                        @click.prevent="sortField(field.key)"
                                    >
                                        {{ field.label ? field.label : "" }}
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody
                            v-for="(item, key) in data"
                            :key="key"
                            class="
                                bg-white
                                border-b-2 border-gray-200
                                hover:bg-gray-50
                            "
                        >
                            <tr>
                                <td
                                    v-for="(field, fieldKey) in topRowFields"
                                    :key="fieldKey"
                                    class="py-2 px-6"
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
                            >
                                <td
                                    v-for="(field, fieldKey) in bottomRowFields"
                                    :key="fieldKey"
                                    class="px-6"
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

export default {
    name: "DataTable",

    components: { DataGridTableField },

    props: {
        data: {
            type: Array,
            default: () => {},
        },
        fields: {
            type: Array,
            default: () => {},
        },
        fieldRows: {
            type: Array,
            default: () => {},
        },
        sort: {
            type: Object,
            default: () => {},
        },
    },

    emits: ["update:sort"],

    data() {
        return {
            sorts: {},
        };
    },

    computed: {
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

    methods: {
        filterFields(fields) {
            return fields.filter((field) => {
                return field.visible;
            });
        },
        sortField(field) {
            this.sorts[field] = _.has(this.sorts, field)
                ? !this.sorts[field]
                : true;

            this.$emit("update:sort", this.sorts);
        },
        click(item, field) {
            this.emitter.emit(field.events.click, item);
        },
    },
};
</script>

<style scoped></style>
