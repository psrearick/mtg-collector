<template>
    <card-index-data-grid
        v-model:card-term="cardSearchTerm"
        v-model:set-term="setSearchTerm"
        v-model:searching="searching"
        :grid-name="gridName"
        :data="cardData.data"
        :fields="table.fields"
        :show-search="true"
        :show-pagination="true"
        :filter="table.filter"
        :pagination="cards"
        :hide-without-data="true"
    />
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import CardIndexDataGrid from "@/Components/DataGrid/CardIndexDataGrid/CardIndexDataGrid";
import CardsIndexTable from "@/Shared/TableDefinitions/CardsIndexTable";

export default {
    name: "CardIndex",

    components: { CardIndexDataGrid },

    mixins: [CardsIndexTable],

    layout: Layout,

    props: {
        cards: {
            type: Object,
            default: () => ({ cards: [] }),
        },
        perPage: {
            type: Number,
            default: 0,
        },
        cardQuery: {
            type: String,
            default: "",
        },
        setQuery: {
            type: String,
            default: "",
        },
        sortQuery: {
            type: Object,
            default: () => {},
        },
    },

    title: "MTG Collector - Card Index",

    header: "Cards",

    data() {
        return {
            setSearchTerm: "",
            cardSearchTerm: "",
            searching: false,
            gridName: "card-index",
        };
    },

    computed: {
        cardData() {
            let cards = _.cloneDeep(this.cards);
            if (!cards || !cards.data) {
                return cards;
            }

            if (Array.isArray(cards.data)) {
                return cards;
            }

            cards.data = Object.values(cards.data);

            return cards;
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
            if (this.cardSearchTerm !== this.cardQuery) {
                this.search();
            }
        },
        setSearchTerm() {
            if (this.setSearchTerm !== this.setQuery) {
                this.search();
            }
        },
    },

    created() {
        this.mount();
        this.emitter.on("card_name_click", (card) => {
            this.showCard(card.id);
        });
        this.emitter.on("sort", (gridName) => {
            if (gridName === this.gridName) {
                this.search();
            }
        });
    },

    methods: {
        mount() {
            this.cardSearchTerm = this.cardQuery;
            this.setSearchTerm = this.setQuery;
            this.$store.dispatch("setSortFields", {
                gridName: this.gridName,
                fields: this.sortQuery,
            });
        },
        showCard(id) {
            this.$inertia.get(`/cards/cards/${id}`);
        },
        search: _.debounce(function () {
            this.table.data = [];
            this.searching = true;
            this.$inertia.get(
                "/cards/search",
                {
                    card: this.cardSearchTerm,
                    set: this.setSearchTerm,
                    sort: this.sortFields,
                },
                {
                    onSuccess: () => {
                        this.searching = false;
                    },
                }
            );
        }, 1200),
    },
};
</script>
