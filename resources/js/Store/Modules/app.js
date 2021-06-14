const state = () => ({
    header: "",
    subheader: "",
    headerRightComponent: null,
    currentRoute: "",
});

const getters = {
    header: (state) => state.header,
    subheader: (state) => state.subheader,
    headerRightComponent: (state) => state.headerRightComponent,
    currentRoute: (state) => state.currentRoute,
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
};

const mutations = {
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
