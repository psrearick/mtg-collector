function table() {
    return {
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
                label: "Today",
                key: "today",
            },
            {
                visible: true,
                type: "text",
                label: "Acquired Date",
                key: "acquired_date",
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
    };
}

export default table();
