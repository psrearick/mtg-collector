const state = () => ({
    sortFields: {},
    sortOrder: {},
    filters: {},
});

const getters = {
    sortFields: (state) => state.sortFields,
    sortOrder: (state) => state.sortOrder,
    filters: (state) => state.filters,
};

const actions = {
    addFieldToSort({ commit }, field) {
        commit("addFieldToSort", field);
    },
    setSortFields({ commit }, fields) {
        commit("setSortFields", fields);
    },
    setSortOrder({ commit }, order) {
        commit("setSortOrder", order);
    },
    setFilters({ commit }, filters) {
        commit("setFilters", filters);
    },
};

const mutations = {
    addFieldToSort(state, { gridName, field }) {
        if (!field.sortable) {
            return;
        }

        if (!state.sortFields[gridName]) {
            state.sortFields[gridName] = {};
        }

        let fieldname = field["key"];
        let direction = "asc";

        if (fieldname in state.sortFields[gridName]) {
            direction = "desc";
            if (state.sortFields[gridName][fieldname] === "desc") {
                delete state.sortFields[gridName][fieldname];
                direction = null;
            }
        }

        if (direction) {
            state.sortFields[gridName][fieldname] = direction;
        }
    },
    setSortFields(state, { gridName, fields }) {
        if (Array.isArray(fields)) {
            fields = {};
        }
        state.sortFields[gridName] = fields;
    },
    setSortOrder(state, { gridName, order }) {
        state.sortOrder[gridName] = order;
    },
    setFilters(state, { gridName, filters }) {
        state.filters[gridName] = filters;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
