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
            <CardIndexDataGrid
                v-model:card-term="cardSearchTerm"
                v-model:searching="searching"
                :data="setCards"
                :field-rows="table.fieldRows"
                :show-pagination="false"
                :force-show="true"
                :show-search="true"
                :set-search="false"
            />
        </div>
    </div>
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import PrimaryButton from "@/Components/Buttons/PrimaryButton";
import SearchSelect from "@/Components/Form/SearchSelect/SearchSelect";
import SuccessButton from "@/Components/Buttons/SuccessButton";
import CardIndexDataGrid from "@/Components/DataGrid/CardIndexDataGrid/CardIndexDataGrid";
import AddFromSetTable from "@/Shared/TableDefinitions/AddFromSetTable";

export default {
    name: "AddFromSet",

    components: { SearchSelect, SuccessButton, CardIndexDataGrid },

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
    },

    data() {
        return {
            allSets: [],
            cardSearchTerm: "",
            searching: false,
            searchTerm: "",
            // selectedSet: -1,
            // setId: null,
        };
    },

    watch: {
        cardSearchTerm() {
            // if (this.cardSearchTerm !== this.cardQuery && this.loaded) {
            this.querySetCards();
            // }
        },
    },

    mounted() {
        this.$store.dispatch("updateHeader", {
            header: "Edit: " + this.collection.name,
        });
        this.$store.dispatch("updateSubheader", {
            subheader: this.collection.description,
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
        // this.selectedSet = this.selected;
    },

    methods: {
        mount: function () {
            this.cardSearchTerm = this.collection.cardQuery;
        },
        querySetCards: _.debounce(function () {
            this.searching = true;
            this.$inertia.reload({
                data: {
                    card: this.cardSearchTerm,
                },
                only: ["setCards"],
                onSuccess: () => {
                    this.searching = false;
                    this.mount();
                },
            });
        }),
        queryCards: function (set) {
            if (set < 0) {
                return;
            }
            this.$inertia.reload({
                data: {
                    set: set,
                },
                only: ["setCards", "selected"],
                // onSuccess: () => {
                //     this.setId = set;
                // },
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
