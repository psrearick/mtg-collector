import CollectionSetBottomRowImage from "@/Components/DataGrid/CollectionSetDataGrid/CollectionSetBottomRowImage";
import CollectionSetBottomRowEntries from "@/Components/DataGrid/CollectionSetDataGrid/CollectionSetBottomRowEntries";

export default {
    data() {
        return {
            table: {
                fieldRows: [
                    {
                        row: 1,
                        fields: [
                            {
                                visible: true,
                                type: "text",
                                label: "Number",
                                key: "number",
                                link: true,
                                events: {
                                    hover: "set_card_name_hover",
                                    click: "expand_collection_card",
                                },
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
                                    click: "expand_collection_card",
                                },
                            },
                            {
                                visible: true,
                                type: "component",
                                component: "PrimaryButton",
                                label: "",
                                link: true,
                                events: {
                                    click: "add_new_row",
                                },
                                props: {
                                    text: "Add to Collection",
                                },
                            },
                            // {
                            //     visible: true,
                            //     type: "currency",
                            //     label: "Price",
                            //     key: "price",
                            // },
                            // {
                            //     visible: true,
                            //     type: "currency",
                            //     label: "Acquired Price",
                            //     key: "acquired_price",
                            // },
                            // {
                            //     visible: true,
                            //     type: "text",
                            //     label: "Acquired Date",
                            //     key: "acquired_date",
                            // },
                            // {
                            //     visible: true,
                            //     type: "component",
                            //     component: HorizontalIncrementer,
                            //     label: "Quantity",
                            //     key: "quantity",
                            //     componentType: "small",
                            // },
                            // {
                            //     visible: true,
                            //     type: "text",
                            //     link: true,
                            //     value: "Foil",
                            //     condition: this.showFoil,
                            //     events: {
                            //         click: "set_card_foil_click",
                            //     },
                            // },
                            // {
                            //     visible: true,
                            //     type: "text",
                            //     link: true,
                            //     value: "Edit",
                            //     events: {
                            //         click: "set_card_edit_click",
                            //     },
                            // },
                        ],
                    },
                    {
                        row: 2,
                        fields: [
                            {
                                visible: true,
                                type: "component",
                                component: CollectionSetBottomRowImage,
                            },
                            {
                                visible: true,
                                type: "component",
                                component: CollectionSetBottomRowEntries,
                            },
                            {
                                visible: true,
                                type: "text",
                                value: "",
                            },
                        ],
                    },
                ],
            },
        };
    },

    created() {
        this.emitter.on("expand_collection_card", (card) => {
            this.$store.dispatch("toggleShownRow", card.id);
            let shown = this.$store.getters.isShownRow(card.id);
            let setCard = this.$store.getters.setCard(card.id);
            if (!setCard) {
                this.getCard(card);
            }

            this.emitter.emit("expand_collection_card_element", {
                card,
                shown,
            });
        });
    },

    methods: {
        getCard(card) {
            const collection = this.$store.getters.currentCollection;

            axios
                .get(
                    `/collections/collections/${collection.id}/set/card/${card.id}`
                )
                .then((res) => {
                    this.$store.dispatch("addSetCard", res.data);
                });
        },
    },
};
