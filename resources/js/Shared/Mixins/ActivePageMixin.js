export default {
    created() {
        if (!this.$options.layout) {
            return;
        }

        if (this.$options.layout.name !== "Authenticated") {
            return;
        }

        this.emitter.emit("currentRoute", route().current());
    },
};
