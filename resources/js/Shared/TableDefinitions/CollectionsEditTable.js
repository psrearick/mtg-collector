export default {
    data() {
        return {
            table: {
                fields: [
                    {
                        visible: true,
                        type: "composite-text",
                        link: true,
                        label: "Card",
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
                        type: "currency",
                        label: "Value",
                        key: "today",
                    },
                    {
                        visible: false,
                        type: "text",
                        label: "Acquired Date",
                        key: "acquired_date",
                    },
                    {
                        visible: false,
                        type: "currency",
                        label: "Acquired Price",
                        key: "acquired_price",
                    },
                    {
                        visible: true,
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
        };
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
};
