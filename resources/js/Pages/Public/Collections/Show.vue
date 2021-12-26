<template>
    <div>
        <CollectionsShowCardList :summary="collection.summary" />
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
    </div>
</template>
<script>
import Layout from "@/Layouts/Public";
import CardIndexDataGrid from "@/Components/DataGrid/CardIndexDataGrid/CardIndexDataGrid";
import CollectionsShowCardList from "@/Components/CardLists/CollectionsShowCardList";
import CollectionsShowTable from "@/Shared/TableDefinitions/CollectionsShowTable";

export default {
    name: "Show",

    components: {
        CollectionsShowCardList,
        CardIndexDataGrid,
    },

    mixins: [CollectionsShowTable],

    layout: Layout,

    title: "MTG Collector - Collection",

    props: {
        collection: {
            type: Object,
            default: () => {},
        },
        user: {
            type: String,
            default: "",
        },
    },

    data() {
        return {
            setSearchTerm: "",
            cardSearchTerm: "",
            loaded: false,
            searching: false,
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
        let headerText = this.collection.name;
        if (this.user) {
            headerText = headerText + " (" + this.user + ")";
        }
        this.$store.dispatch("updateHeader", { header: headerText });
        this.$store.dispatch("updateSubheader", {
            subheader: this.collection.description,
        });
    },

    created() {
        this.mount();
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
            this.$inertia.get("/public-collections/" + this.collection.id, {
                cardSearch: this.cardSearchTerm,
                setSearch: this.setSearchTerm,
                sort: this.sortFields,
                sortOrder: this.sortOrder,
                filters: this.filters,
            });
        }, 1200),
    },
};
</script>
