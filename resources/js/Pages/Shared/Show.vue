<template>
    <div>
        <div>Saved</div>
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
        shared: {
            type: Object,
            default: () => {},
        },
        user: {
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
        let subheaderText = this.collection.description;
        if (this.user) {
            headerText =
                headerText +
                " <span class='text-base text-gray-400 font-normal'>(" +
                this.user.name +
                ")</span>";

            if (this.$page.props.auth.user) {
                if (this.user.id !== this.$page.props.auth.user.id) {
                    this.$store.dispatch("updateHeaderRightComponent", {
                        component: {
                            is: "UiButton",
                            props: {
                                text: "Remove from Shared Collections",
                                emit: "remove-from-shared-collection",
                                "button-style": "primary-dark",
                            },
                        },
                    });
                }
            }
        }
        this.$store.dispatch("updateHeader", { header: headerText });
        this.$store.dispatch("updateSubheader", {
            subheader: subheaderText,
        });
    },

    created() {
        this.mount();
        this.emitter.on("remove-from-shared-collection", () => {
            this.$inertia.delete(`/shared/shared/${this.shared.id}`);
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
            this.$inertia.get("/shared/shared/" + this.collection.id, {
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
