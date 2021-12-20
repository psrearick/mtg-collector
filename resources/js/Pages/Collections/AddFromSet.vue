<template>
    <div>
        <div class="flex justify-between flex-wrap">
            <h3 class="text-lg leading-6 font-medium text-gray-900 py-4 px-4">
                Select a Set
            </h3>
            <div class="py-4 px-4">
                <SuccessButton
                    type="button"
                    text="Add Cards by Name"
                    :href="route('collections.edit', [page.collection.id])"
                />
            </div>
        </div>

        <div class="md:flex md:flex-wrap mb-12">
            <search-select
                v-model:search-term="setSearchTerm"
                class="md:flex-1 max-w-full"
                :selected="page.selectedIndex"
                label="Select a Set"
                :options="sets"
                :searching="setSearching"
                @update:selected-option="selectSet"
            />
            <div class="py-6">
                <primary-button
                    class="ml-2"
                    type="button"
                    @click="resetSetField"
                >
                    Reset
                </primary-button>
            </div>
        </div>

        <div class="w-full">
            <card-set-search
                v-model="cardSearchTerm"
                :set-search="false"
                :searching="dataGridSearching"
            />
            <collection-set-card-search-results :search="cardSearchTerm" />
        </div>
    </div>
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import SearchSelect from "@/Components/Form/SearchSelect/SearchSelect";
import SuccessButton from "@/Components/Buttons/SuccessButton";
import CollectionSetCardSearchResults from "@/Components/DataGrid/CollectionSetCardSearchResults";
import PrimaryButton from "@/Components/Buttons/PrimaryButton";
import CardSetSearch from "@/Components/Form/CardSearch/CardSetSearch";
import UpdateCardQuantityMixin from "@/Shared/Mixins/UpdateCardQuantityMixin";

export default {
    name: "AddFromSet",

    components: {
        SearchSelect,
        SuccessButton,
        PrimaryButton,
        CollectionSetCardSearchResults,
        CardSetSearch,
    },

    mixins: [UpdateCardQuantityMixin],

    layout: Layout,

    title: "MTG Collector - Add Set to Collection",

    props: {
        page: {
            type: Object,
            default: () => {},
        },
    },

    data() {
        return {
            setId: null,
            cardSearchTerm: "",
            setSearchTerm: "",
            setSearching: false,
            dataGridSearching: false,
            loaded: false,
        };
    },

    computed: {
        sets() {
            return this.page.sets.map((set) => {
                return {
                    primary: set.name,
                    secondary: set.code.toUpperCase(),
                    id: set.id,
                };
            });
        },
    },

    watch: {
        cardSearchTerm() {
            if (this.cardSearchTerm !== this.page.cardSearch && this.loaded) {
                if (!this.setId) {
                    return;
                }
                this.searchCards();
            }
        },
        setSearchTerm() {
            if (this.setSearchTerm !== this.page.setSearch && this.loaded) {
                this.searchSets();
            }
        },
    },

    mounted() {
        this.$store.dispatch("updateHeader", {
            header: "Edit: " + this.page.collection.name,
        });
        this.$store.dispatch("updateSubheader", {
            subheader: "Add Cards to Collection by Set",
        });
        this.$store.dispatch("updateHeaderRightComponent", {
            component: {
                is: "PrimaryButton",
                props: {
                    text: "Done Editing",
                    href: route("collections.show", {
                        collection: this.page.collection.id,
                    }),
                },
            },
        });
        this.$store.dispatch("updateCurrentCollection", {
            collection: this.page.collection,
        });
        this.mount();
    },

    methods: {
        mount: function () {
            this.cardSearchTerm = this.page.cardSearch;
            this.setSearchTerm = this.page.setSearch;
            this.setId = this.page.setId;
            this.loaded = true;
            this.$store.dispatch("addCardSearchResults", {
                searchResults: this.page.cards,
            });
        },
        query: function () {
            this.$inertia.reload({
                data: {
                    set: this.setId,
                    setSearch: this.setSearchTerm,
                    cardSearch: this.cardSearchTerm,
                },
                onSuccess: () => {
                    this.dataGridSearching = false;
                    this.setSearching = false;
                    this.mount();
                },
            });
        },
        resetSetField() {
            this.setSearching = true;
            this.dataGridSearching = true;
            this.setId = null;
            this.setSearchTerm = "";
            this.cardSearchTerm = "";
            this.query();
        },
        searchCards: _.debounce(function () {
            this.dataGridSearching = true;
            this.query();
        }, 1200),
        searchSets: _.debounce(function () {
            this.setSearching = true;
            this.sets = [];
            this.query();
        }, 1200),
        selectSet: function (set) {
            if (set < 0) {
                return;
            }
            this.setSearching = true;
            this.setId = set;
            this.query();
        },
        // unsetResults: function () {
        //     this.$store.dispatch("unsetShownRows");
        //     this.$store.dispatch("unsetSetCards");
        // },
    },
};
</script>
