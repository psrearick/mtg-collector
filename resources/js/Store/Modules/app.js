const state = () => ({
    header: "",
    subheader: "",
    headerRightComponent: null,
    currentRoute: "",
    emitters: [],
});

const getters = {
    header: (state) => state.header,
    subheader: (state) => state.subheader,
    headerRightComponent: (state) => state.headerRightComponent,
    currentRoute: (state) => state.currentRoute,
    emitters: (state) => state.emitters,
};

const actions = {
    updateHeader({ commit }, header) {
        commit("setHeader", header);
    },
    updateSubheader({ commit }, subHeader) {
        commit("setSubheader", subHeader);
    },
    updateHeaderRightComponent({ commit }, headerRightComponent) {
        commit("setHeaderRightComponent", headerRightComponent);
    },
    updateCurrentRoute({ commit }, currentRoute) {
        commit("setCurrentRoute", currentRoute);
    },
    addEmitter({ commit }, emitter) {
        commit("addEmitter", emitter);
    },
};

const mutations = {
    addEmitter(state, emitter) {
        state.emitters.push(emitter);
    },
    setHeader(state, { header }) {
        state.header = header;
    },
    setSubheader(state, { subheader }) {
        state.subheader = subheader;
    },
    setHeaderRightComponent(state, { component }) {
        state.headerRightComponent = component;
    },
    setCurrentRoute(state, { currentRoute }) {
        state.currentRoute = currentRoute;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
