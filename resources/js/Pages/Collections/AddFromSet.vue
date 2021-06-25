<template>
    <div>
        <div class="flex justify-between">
            <h3 class="text-lg leading-6 font-medium text-gray-900 py-4">
                Select a Set
            </h3>
            <div class="py-4">
                <SuccessButton
                    type="button"
                    text="Add Cards by Name"
                    :href="route('collections.edit', [collection.id])"
                />
            </div>
        </div>

        <SearchSelect
            class="mb-12"
            :selected="selected"
            label="Select a Set"
            :options="allSets"
            :search-term="searchTerm"
            :searching="searching"
            @update:selectedOption="queryCards"
            @update:searchTerm="querySets"
        />

        <div class="w-full">
            <DataGrid
                v-model:search-term="cardSearchTerm"
                v-model:searching="dataGridSearching"
                :show-search="true"
                :show-pagination="false"
                :data="setCards"
                :fields="table.fields"
                :classes="dataGridStyles"
            />
        </div>
    </div>
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import PrimaryButton from "@/Components/Buttons/PrimaryButton";
import SearchSelect from "@/Components/Form/SearchSelect/SearchSelect";
import SuccessButton from "@/Components/Buttons/SuccessButton";
import AddFromSetTable from "@/Shared/TableDefinitions/AddFromSetTable";
import DataGrid from "@/Components/DataGrid/DataGrid";

export default {
    name: "AddFromSet",

    components: { SearchSelect, SuccessButton, DataGrid },

    mixins: [AddFromSetTable],

    layout: Layout,

    title: "MTG Collector - Add Set to Collection",

    props: {
        collection: {
            type: Object,
            default: () => {},
        },
        setCards: {
            type: Object,
            default: () => {},
        },
        setSets: {
            type: Array,
            default: () => {},
        },
        selected: {
            type: Number,
            default: null,
        },
        queryString: {
            type: String,
            default: "",
        },
        setCardQuery: {
            type: String,
            default: "",
        },
    },

    data() {
        return {
            allSets: [],
            cardSearchTerm: "",
            searching: false,
            dataGridSearching: false,
            searchTerm: "",
            dataGridStyles: {
                tableCell: "py-1 px-6",
            },
            loaded: false,
        };
    },

    watch: {
        cardSearchTerm() {
            if (this.cardSearchTerm !== this.setCardQuery && this.loaded) {
                this.querySetCards();
            }
        },
    },

    mounted() {
        this.$store.dispatch("updateHeader", {
            header: "Add Cards to Collection by Set",
        });
        this.$store.dispatch("updateSubheader", {
            subheader: "Editing " + this.collection.name,
        });
        this.$store.dispatch("updateHeaderRightComponent", {
            component: {
                is: PrimaryButton,
                props: {
                    text: "Done Editing",
                    href: route("collections.show", {
                        collection: this.collection.id,
                    }),
                },
            },
        });
        this.mapSets();
        this.mount();
    },

    methods: {
        mount: function () {
            this.cardSearchTerm = this.setCardQuery;
            this.loaded = true;
        },
        querySetCards: _.debounce(function () {
            this.dataGridSearching = true;
            this.$inertia.reload({
                data: {
                    card: this.cardSearchTerm,
                },
                only: ["setCards", "setCardQuery"],
                onSuccess: () => {
                    this.dataGridSearching = false;
                    this.mount();
                },
            });
        }, 1200),
        queryCards: function (set) {
            if (set < 0) {
                return;
            }
            this.$inertia.reload({
                data: {
                    set: set,
                },
                only: ["setCards", "selected"],
            });
        },
        querySets: _.debounce(function (term) {
            this.searching = true;
            this.allSets = [];
            this.$inertia.reload({
                data: {
                    query: term,
                },
                only: ["setSets", "queryString"],
                onSuccess: () => {
                    this.searching = false;
                    this.mapSets();
                },
            });
        }, 1200),
        mapSets: function () {
            this.searchTerm = this.queryString;
            this.allSets = this.setSets.map((set) => {
                return {
                    primary: set.name,
                    secondary: set.code,
                    id: set.id,
                };
            });
        },
    },
};
</script>
