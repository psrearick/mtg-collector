import HorizontalIncrementer from "@/Components/Buttons/HorizontalIncrementer";

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
                                key: "foil_formatted",
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
                        component: HorizontalIncrementer,
                        label: "Quantity",
                        key: "quantity",
                    },
                    {
                        visible: true,
                        type: "text",
                        link: true,
                        value: "Edit",
                        events: {
                            click: "collection_card_edit_click",
                        },
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
                foil: card.foil,
            });
        });

        this.emitter.on("decrementQuantity", (card) => {
            this.emitter.emit("updateCardQuantity", {
                change: -1,
                id: card.id,
                foil: card.foil,
            });
        });
    },
};
