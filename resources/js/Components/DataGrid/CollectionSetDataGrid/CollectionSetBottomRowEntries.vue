<template>
    <div v-show="show"></div>
</template>

<script>
export default {
    name: "CollectionSetBottomRowEntries",

    props: {
        data: {
            type: Object,
            default: () => {},
        },
    },

    data() {
        return {
            show: false,
            defaultCard: {
                features: "",
                finishes: [],
                id: null,
                image_url: "",
                name: "",
                number: null,
                prices: [],
            },
        };
    },

    computed: {
        shown() {
            if (!this.data.id) {
                return false;
            }
            return this.$store.getters.isShownRow(this.data.id);
        },
        card() {
            if (!this.data.id) {
                return this.defaultCard;
            }

            const setCard = this.$store.getters.setCard(this.data.id);
            return setCard ? setCard : this.defaultCard;
        },
    },

    created() {
        this.emitter.on("expand_collection_card_element", ({ card, shown }) => {
            if (card.id !== this.data.id) {
                return;
            }

            this.show = shown;
        });

        this.emitter.on("add_new_row", (card) => {
            if (card.id !== this.data.id) {
                return;
            }
            if (!this.show) {
                this.emitter.emit("expand_collection_card", card);
            }
            // console.log("click", card);
        });
    },
};
</script>
