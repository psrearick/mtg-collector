import { createStore } from "vuex";
import app from "./Modules/app";
import CollectionCards from "./Modules/CollectionCards";
import Collections from "./Modules/Collections";
import Search from "./Modules/Search";

export default createStore({
    modules: {
        app,
        CollectionCards,
        Collections,
        Search,
    },
});
