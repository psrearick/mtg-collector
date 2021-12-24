<template>
    <div>
        <div class="mb-12">
            <div class="flex justify-between">
                <h3 class="text-lg leading-6 font-medium text-gray-900 py-4">
                    Add Cards to Collection
                </h3>
                <div class="py-4">
                    <inertia-link
                        :href="
                            route('collection-set.edit', [page.collection.id])
                        "
                    >
                        <ui-button
                            text="Add Cards by Set"
                            button-style="success-outline"
                        >
                        </ui-button>
                    </inertia-link>
                </div>
            </div>
            <div class="w-full">
                <card-search />
            </div>
        </div>
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 my-4">
                Edit Collection
            </h3>
            <div class="w-full">
                <card-index-data-grid
                    v-model:card-term="cardSearchTerm"
                    v-model:set-term="setSearchTerm"
                    v-model:searching="searching"
                    :data="filteredCollection.data"
                    :fields="table.fields"
                    :select-menu="table.selectMenu"
                    :show-pagination="true"
                    :force-show="true"
                    :show-search="true"
                    :pagination="filteredCollection"
                    :grid-name="gridName"
                />
            </div>
        </div>
        <move-to-collection-panel
            v-model:show="moveToCollectionPanelShow"
            :data="moveToCollectionPanelData"
            :collection="page.collection"
            @saved="clearDataGrid"
            @close="clearPanelData"
        />
        <remove-from-collection-panel
            v-model:show="removeFromCollectionPanelShow"
            :data="removeFromCollectionPanelData"
            :collection="page.collection"
            @saved="clearDataGrid"
        />
    </div>
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import CardSearch from "@/Components/Form/CardSearch/CardSearch";
import CardIndexDataGrid from "@/Components/DataGrid/CardIndexDataGrid/CardIndexDataGrid";
import CollectionsEditTable from "@/Shared/TableDefinitions/CollectionsEditTable";
import UpdateCardQuantityMixin from "@/Shared/Mixins/UpdateCardQuantityMixin";
import SuccessButton from "@/Components/Buttons/SuccessButton";
import MoveToCollectionPanel from "@/Components/Panels/MoveToCollectionPanel";
import UiButton from "@/UI/UIButton.vue";
import RemoveFromCollectionPanel from "@/Components/Panels/RemoveFromCollectionPanel";

export default {
    name: "EditCollection",

    components: {
        SuccessButton,
        CardIndexDataGrid,
        CardSearch,
        MoveToCollectionPanel,
        RemoveFromCollectionPanel,
        UiButton,
    },

    mixins: [CollectionsEditTable, UpdateCardQuantityMixin],

    layout: Layout,

    title: "MTG Collector - Edit Collection",

    props: {
        page: {
            type: Object,
            default: () => {},
        },
    },

    data() {
        return {
            setSearchTerm: "",
            cardSearchTerm: "",
            loaded: false,
            searching: false,
            filteredCollection: {},
            removeFromCollectionPanelShow: false,
            removeFromCollectionPanelData: {},
            moveToCollectionPanelShow: false,
            moveToCollectionPanelData: {},
        };
    },

    computed: {
        cardData() {
            let cards = _.cloneDeep(this.page.cards);
            if (!cards || !cards.data) {
                return cards;
            }

            if (Array.isArray(cards.data)) {
                return cards;
            }

            cards.data = Object.values(cards.data);

            return cards;
        },
    },

    watch: {
        cardSearchTerm() {
            if (this.cardSearchTerm !== this.page.cardQuery && this.loaded) {
                this.search();
            }
        },
        setSearchTerm() {
            if (this.setSearchTerm !== this.page.setQuery && this.loaded) {
                this.search();
            }
        },
        "page.cards.data": {
            deep: true,
            handler() {
                this.$store.dispatch("addFilteredCollection", {
                    collection: this.cardData,
                });
                this.filteredCollection =
                    this.$store.getters.filteredCollection;
            },
        },
    },

    mounted() {
        this.$store.dispatch("updateHeader", {
            header: "Edit: " + this.page.collection.name,
        });
        this.$store.dispatch("updateSubheader", {
            subheader: this.page.collection.description,
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
    },

    created() {
        this.mount();
        this.$store.dispatch("addFilteredCollection", {
            collection: this.cardData,
        });
    },

    methods: {
        clearDataGrid() {
            this.emitter.emit("clear_data_grid_selections", this.gridName);
        },
        clearPanelData() {
            this.moveToCollectionPanelData = {};
        },
        mount() {
            this.cardSearchTerm = this.page.cardQuery;
            this.setSearchTerm = this.page.setQuery;
            this.setSort();
            this.loaded = true;
            this.searching = false;
            this.$store.dispatch("addFilteredCollection", {
                collection: this.cardData,
            });
            this.filteredCollection = this.$store.getters.filteredCollection;
        },
        search: _.debounce(function () {
            this.searching = true;
            this.$inertia.get(
                "/collections/collections/" + this.page.collection.id + "/edit",
                {
                    cardSearch: this.cardSearchTerm,
                    setSearch: this.setSearchTerm,
                    sort: this.sortFields,
                    sortOrder: this.sortOrder,
                }
            );
        }, 1200),
    },
};
</script>
