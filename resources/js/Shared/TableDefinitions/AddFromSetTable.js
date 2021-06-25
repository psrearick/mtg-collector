import HorizontalIncrementer from "@/Components/Buttons/HorizontalIncrementer";

export default {
    data() {
        return {
            table: {
                fields: [
                    {
                        visible: true,
                        type: "text",
                        label: "Number",
                        key: "number",
                    },
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
                                key: "features",
                                classes: "text-sm text-gray-500 pl-2",
                            },
                        ],
                        events: {
                            hover: "set_card_name_hover",
                            click: "collection_card_name_click",
                        },
                    },
                    {
                        visible: true,
                        type: "currency",
                        label: "Price",
                        key: "price",
                    },
                    {
                        visible: true,
                        type: "currency",
                        label: "Acquired Price",
                        key: "acquired_price",
                    },
                    {
                        visible: true,
                        type: "text",
                        label: "Acquired Date",
                        key: "acquired_date",
                    },
                    {
                        visible: true,
                        type: "component",
                        component: HorizontalIncrementer,
                        label: "Quantity",
                        key: "quantity",
                        componentType: "small",
                    },
                    {
                        visible: true,
                        type: "text",
                        link: true,
                        value: "Foil",
                        condition: this.showFoil,
                        events: {
                            click: "set_card_foil_click",
                        },
                    },
                    {
                        visible: true,
                        type: "text",
                        link: true,
                        value: "Edit",
                        events: {
                            click: "set_card_edit_click",
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

        this.emitter.on("set_card_foil_click", (card) => {
            this.updateQuantity({
                change: 1,
                id: card.id,
                foil: true,
            });
        });

        this.emitter.on("incrementQuantity", (card) => {
            this.updateQuantity({
                change: 1,
                id: card.id,
                foil: card.is_foil,
            });
        });

        this.emitter.on("decrementQuantity", (card) => {
            this.updateQuantity({
                change: -1,
                id: card.id,
                foil: card.is_foil,
            });
        });
    },

    methods: {
        updateQuantity(change) {
            axios
                .post("/card-collections/card-collections", {
                    change: change,
                    collection: this.collection.id,
                })
                .then(() => {
                    this.$inertia.reload({
                        only: ["collection", "setCards"],
                    });
                });
        },
        showFoil(item) {
            return !(item.own_foil || item.is_foil);
        },
    },
};
