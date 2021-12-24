export default {
    data() {
        return {
            table: {
                data: [],
                filter: [],
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
                        link: true,
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
                        type: "currency",
                        link: false,
                        label: "Non-Foil",
                        key: "price",
                        sortable: true,
                        filterable: false,
                    },
                    {
                        visible: true,
                        type: "currency",
                        link: false,
                        label: "Foil",
                        key: "price_foil",
                        sortable: true,
                        filterable: false,
                    },
                    {
                        visible: true,
                        type: "currency",
                        link: false,
                        label: "Etched",
                        key: "price_etched",
                        sortable: true,
                        filterable: false,
                    },
                    {
                        visible: true,
                        type: "text",
                        label: "Collected",
                        key: "quantity_collected",
                        sortable: true,
                        filterable: false,
                    },
                    // {
                    //     visible: true,
                    //     type: "text",
                    //     label: "Non-Foil Collected",
                    //     key: "nonfoil_collected",
                    //     sortable: false,
                    //     filterable: true,
                    // },
                    // {
                    //     visible: true,
                    //     type: "text",
                    //     label: "Foil Collected",
                    //     key: "foil_collected",
                    //     sortable: false,
                    //     filterable: true,
                    // },
                ],
            },
            gridName: "card-index",
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
    created() {
        this.emitter.on("card_name_click", (card) => {
            this.showCard(card.id);
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
            fields: this.sortQuery,
        });
        this.$store.dispatch("setSortOrder", {
            gridName: this.gridName,
            order: this.sortOrder || {},
        });
    },
};
