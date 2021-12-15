<template>
    <div v-if="shown">
        <img :src="card.image_url" class="max-h-52 my-4" />
    </div>
</template>

<script>
export default {
    name: "CollectionSetBottomRowImage",

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
    },
};
</script>
