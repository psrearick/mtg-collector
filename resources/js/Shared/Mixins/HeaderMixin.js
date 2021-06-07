function getHeader(vm) {
    const { header } = vm.$options;
    if (header) {
        return typeof header === "function" ? header.call(vm) : header;
    }
}
export default {
    created() {
        const header = getHeader(this);
        if (header) {
            this.emitter.emit("pageTitle", header);
        }
    },
};
