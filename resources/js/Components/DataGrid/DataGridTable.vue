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
                                    v-for="(field, index) in filteredFields"
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
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-for="(item, key) in data.data"
                                :key="key"
                                class="hover:bg-gray-50"
                            >
                                <td
                                    v-for="(field, fieldKey) in filteredFields"
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
        filteredFields() {
            return this.fields.filter((field) => {
                return field.visible;
            });
        },
    },

    methods: {
        sortField(field) {
            // eslint-disable-next-line no-undef
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
