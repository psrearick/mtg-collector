export default {
    props: {
        page: {
            type: Object,
            default: () => {},
        },
    },
    mounted() {
        // this.addCollectionsToStore();
        let clear = { searchResults: [] };
        this.$store.dispatch("addCardSearchResults", clear);
        this.$store.dispatch("addSetSearchResults", clear);
    },
    created() {
        const emitters = this.$store.getters.emitters;
        if (emitters.indexOf("updateCardQuantity") === -1) {
            this.$store.dispatch("addEmitter", "updateCardQuantity");
            this.emitter.on("updateCardQuantity", (change) => {
                this.updateCardQuantity(change);
            });
        }
    },
    methods: {
        updateCardQuantity: function (change) {
            let collection =
                this.$store.getters.currentCollection || this.page.collection;

            axios
                .post("/card-collections/card-collections", {
                    ...change,
                    collection: collection.id,
                })
                .then((res) => {
                    const data = res.data;
                    if (data.error) {
                        return;
                    }

                    this.$store.dispatch(
                        "updateCardSearchResultsCard",
                        data.searchCard
                    );

                    this.$inertia.reload({
                        only: ["page"],
                    });
                });
        },
    },
};
