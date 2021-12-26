<template>
    <div>
        <div>
            <span class="text-lg text-gray-500">Search Collection</span>
        </div>
        <card-set-search
            v-model="cardSearchTerm"
            v-model:set-name="setSearchTerm"
            @gridConfigurationClick="
                gridConfigurationPanelShow = !gridConfigurationPanelShow
            "
        />
        <p v-if="searching" class="text-xs text-gray-400">Searching...</p>
        <data-table
            v-if="cards.length"
            class="mt-4"
            :data="cards"
            :fields="cardsTable.fields"
            :grid-name="gridName"
        />
        <div v-if="collections.length" class="mt-8">
            <span class="text-base text-gray-500">Collections</span>
            <data-table
                class="mt-4"
                :data="collections"
                :fields="collectionsTable.fields"
                :grid-name="collectionsGridName"
            />
        </div>
        <grid-configuration-panel
            v-model:show="gridConfigurationPanelShow"
            :fields="cardsTable.fields"
            :grid-name="gridName"
        />
    </div>
</template>
<script>
import CardSetSearch from "@/Components/Form/CardSearch/CardSetSearch";
import GridConfigurationPanel from "@/Components/Panels/GridConfigurationPanel";
import DataTable from "@/Components/DataGrid/DataGridTable";

export default {
    name: "CollectionSearch",

    components: { CardSetSearch, GridConfigurationPanel, DataTable },

    data() {
        return {
            cardsTable: {
                fields: [
                    {
                        visible: true,
                        type: "text",
                        link: true,
                        hover: true,
                        label: "Card",
                        key: "name",
                        events: {
                            click: "card_name_click",
                            hover: "card_name_hover",
                        },
                        sortable: true,
                        filterable: false,
                    },
                    {
                        visible: true,
                        type: "text",
                        link: false,
                        label: "Set",
                        key: "set",
                        event: "set_name_click",
                        sortable: true,
                        filterable: false,
                    },
                    {
                        visible: true,
                        type: "text",
                        link: false,
                        label: "Features",
                        key: "feature",
                        sortable: false,
                        filterable: false,
                    },
                    {
                        visible: true,
                        type: "text",
                        link: false,
                        label: "Non-Foil",
                        key: "quantity_nonfoil",
                        sortable: true,
                        filterable: false,
                    },
                    {
                        visible: true,
                        type: "text",
                        link: false,
                        label: "Foil",
                        key: "quantity_foil",
                        sortable: true,
                        filterable: false,
                    },
                    {
                        visible: true,
                        type: "text",
                        link: false,
                        label: "Etched",
                        key: "quantity_etched",
                        sortable: true,
                        filterable: false,
                    },
                    {
                        visible: true,
                        type: "text",
                        label: "Total Collected",
                        key: "quantity",
                        sortable: true,
                        filterable: false,
                    },
                    {
                        visible: true,
                        type: "component",
                        component: "UiButton",
                        label: "",
                        link: true,
                        events: {
                            click: "view_collection_card_details",
                        },
                        props: {
                            text: "Details",
                            "button-style": "primary-outline",
                        },
                    },
                ],
            },
            collectionsTable: {
                fields: [
                    {
                        visible: true,
                        type: "text",
                        link: true,
                        hover: true,
                        label: "Card",
                        key: "name",
                        events: {
                            click: "collection_name_click",
                        },
                        sortable: true,
                        filterable: false,
                    },
                    {
                        visible: true,
                        type: "text",
                        link: false,
                        label: "Non-Foil",
                        key: "nonfoil",
                        sortable: true,
                        filterable: false,
                    },
                    {
                        visible: true,
                        type: "text",
                        link: false,
                        label: "Foil",
                        key: "foil",
                        sortable: true,
                        filterable: false,
                    },
                    {
                        visible: true,
                        type: "text",
                        link: false,
                        label: "Etched",
                        key: "etched",
                        sortable: true,
                        filterable: false,
                    },
                    {
                        visible: true,
                        type: "text",
                        label: "Total Collected",
                        key: "total",
                        sortable: true,
                        filterable: false,
                    },
                ],
            },
            cardSearchTerm: "",
            setSearchTerm: "",
            gridName: "dashboard-cards",
            collectionsGridName: "dashboard-collections",
            gridConfigurationPanelShow: false,
            searching: false,
            cards: [],
            collections: [],
        };
    },
    computed: {
        fieldSortOrder() {
            let fields = this.$store.getters.sortOrder;
            if (fields) {
                return fields[this.gridName];
            }

            return {};
        },
        sortFields() {
            let fields = this.$store.getters.sortFields;
            if (fields) {
                return fields[this.gridName];
            }

            return {};
        },
    },
    watch: {
        cardSearchTerm() {
            this.search();
        },
        setSearchTerm() {
            this.search();
        },
    },
    created() {
        this.emitter.on("view_collection_card_details", (card) => {
            this.collections = Object.values(card.collections);
        });
        this.emitter.on("card_name_click", (card) => {
            this.showCard(card.id);
        });
        this.emitter.on("collection_name_click", (collection) => {
            this.showCollection(collection.id);
        });
        this.emitter.on("sort", (gridName) => {
            if (gridName === this.gridName) {
                this.search();
            }
        });
    },
    mounted() {
        this.$store.dispatch("setSortFields", {
            gridName: this.gridName,
            fields: this.sortQuery || {},
        });
        this.$store.dispatch("setSortOrder", {
            gridName: this.gridName,
            order: this.sortOrder || {},
        });
    },
    methods: {
        showCard(id) {
            this.$inertia.get(`/cards/cards/${id}`);
        },
        showCollection(id) {
            this.$inertia.get(`/collections/collections/${id}`);
        },
        search: _.debounce(function () {
            this.cards = [];
            this.collections = [];
            this.searching = true;
            axios
                .post("/collections/search", {
                    card: this.cardSearchTerm,
                    set: this.setSearchTerm,
                    sort: this.sortFields,
                    sortOrder: this.fieldSortOrder,
                })
                .then((res) => {
                    this.cards = res.data;
                    this.searching = false;
                });
        }, 1200),
    },
};
</script>
