export default {
    created() {
        if (!this.$options.layout) {
            return;
        }

        if (this.$options.layout.name !== "Authenticated") {
            return;
        }

        this.$store.dispatch("updateCurrentRoute", {
            currentRoute: route().current(),
        });
        this.$store.dispatch("updateHeader", { header: "" });
        this.$store.dispatch("updateSubheader", { subHeader: "" });
    },
};
