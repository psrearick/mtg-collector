import { createStore } from "vuex";
import app from "./Modules/app";
import CollectionCards from "./Modules/CollectionCards";

export default createStore({
    modules: {
        app,
        CollectionCards,
    },
});
