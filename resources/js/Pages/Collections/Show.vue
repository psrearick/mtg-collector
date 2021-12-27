<template>
    <div>
        <collections-show-card-list :summary="collection.summary" />
        <div>
            <card-index-data-grid
                v-model:card-term="cardSearchTerm"
                v-model:set-term="setSearchTerm"
                v-model:searching="searching"
                :grid-name="gridName"
                :data="cardData.data"
                :fields="table.fields"
                :show-pagination="true"
                :force-show="true"
                :show-search="true"
                :pagination="collection.cards"
            />
        </div>
        <import-collection-panel
            v-model:show="showImportCollectionPanel"
            :collection="collection"
        />
    </div>
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import CardIndexDataGrid from "@/Components/DataGrid/CardIndexDataGrid/CardIndexDataGrid";
import CollectionsShowCardList from "@/Components/CardLists/CollectionsShowCardList";
import CollectionsShowTable from "@/Shared/TableDefinitions/CollectionsShowTable";
import ImportCollectionPanel from "@/Components/Panels/ImportCollectionPanel";

export default {
    name: "ShowCollection",

    components: {
        CollectionsShowCardList,
        CardIndexDataGrid,
        ImportCollectionPanel,
    },

    mixins: [CollectionsShowTable],

    layout: Layout,

    title: "MTG Collector - Collection",

    props: {
        collection: {
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
            showImportCollectionPanel: false,
        };
    },

    computed: {
        cardData() {
            let cards = _.cloneDeep(this.collection.cards);
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
            if (this.cardSearchTerm !== this.cardQuery && this.loaded) {
                this.search();
            }
        },
        setSearchTerm() {
            if (this.setSearchTerm !== this.setQuery && this.loaded) {
                this.search();
            }
        },
    },

    mounted() {
        this.$store.dispatch("updateHeader", { header: this.collection.name });
        this.$store.dispatch("updateSubheader", {
            subheader: this.collection.description,
        });
        this.$store.dispatch("updateHeaderRightComponent", {
            component: {
                is: "CollectionShowHeaderRight",
                props: {
                    collection: this.collection,
                },
            },
        });
    },

    created() {
        this.mount();
        this.emitter.on("import-collection", () => {
            this.showImportCollectionPanel = true;
        });
    },

    methods: {
        mount() {
            this.cardSearchTerm = this.collection.cardQuery;
            this.setSearchTerm = this.collection.setQuery;
            this.setSort();
            this.loaded = true;
            this.searching = false;
        },
        search: _.debounce(function () {
            this.searching = true;
            this.$inertia.get(
                "/collections/collections/" + this.collection.id,
                {
                    cardSearch: this.cardSearchTerm,
                    setSearch: this.setSearchTerm,
                    sort: this.sortFields,
                    sortOrder: this.sortOrder,
                    filters: this.filters,
                }
            );
        }, 1200),
    },
};
</script>
