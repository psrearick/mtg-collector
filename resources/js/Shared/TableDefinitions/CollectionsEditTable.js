export default {
    data() {
        return {
            table: {
                fields: [
                    {
                        visible: true,
                        sortable: true,
                        type: "composite-text",
                        link: true,
                        label: "Card",
                        key: "name",
                        values: [
                            {
                                key: "name",
                                classes: "",
                            },
                            {
                                key: "finish",
                                classes: "text-sm text-gray-500 pl-2",
                            },
                        ],
                        events: {
                            click: "collection_card_name_click",
                        },
                    },
                    {
                        visible: true,
                        sortable: true,
                        type: "text",
                        link: false,
                        label: "Set",
                        key: "set",
                    },
                    {
                        visible: true,
                        type: "text",
                        label: "Features",
                        key: "features",
                    },
                    {
                        visible: true,
                        sortable: true,
                        type: "currency",
                        label: "Value",
                        key: "price",
                    },
                    {
                        visible: false,
                        sortable: true,
                        type: "text",
                        label: "Acquired Date",
                        key: "acquired_date",
                    },
                    {
                        visible: false,
                        sortable: true,
                        type: "currency",
                        label: "Acquired Price",
                        key: "acquired_price",
                    },
                    {
                        visible: true,
                        sortable: true,
                        type: "component",
                        component: "HorizontalIncrementer",
                        label: "Quantity",
                        key: "quantity",
                    },
                ],
                selectMenu: [
                    {
                        content: "Move to Collection",
                        action: "move_to_collection",
                    },
                    {
                        content: "Remove from Collection",
                        action: "remove_from_collection",
                    },
                ],
            },
            gridName: "collection-edit",
        };
    },
    computed: {
        sortFields() {
            let fields = this.$store.getters.sortFields;
            if (fields) {
                return fields[this.gridName];
            }

            return {};
        },
    },
    created() {
        this.emitter.on("collection_card_name_click", (card) => {
            this.$inertia.get(`/cards/cards/${card.id}`);
        });
        this.emitter.on("incrementQuantity", (card) => {
            this.emitter.emit("updateCardQuantity", {
                change: 1,
                id: card.id,
                finish: card.finish,
            });
        });
        this.emitter.on("sort", (gridName) => {
            if (gridName === this.gridName) {
                this.search();
            }
        });
        this.emitter.on("decrementQuantity", (card) => {
            this.emitter.emit("updateCardQuantity", {
                change: -1,
                id: card.id,
                finish: card.finish,
            });
        });
        this.emitter.on("move_to_collection", (data) => {
            this.moveToCollectionPanelData = data;
            this.moveToCollectionPanelShow = true;
        });
        this.emitter.on("remove_from_collection", (data) => {
            this.removeFromCollectionPanelData = data;
            this.removeFromCollectionPanelShow = true;
        });
    },
    methods: {
        setSort() {
            this.$store.dispatch("setSortFields", {
                gridName: this.gridName,
                fields: this.page.sortQuery || {},
            });
        },
    },
};
