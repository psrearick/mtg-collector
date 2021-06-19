import { createStore } from "vuex";
import app from "./Modules/app";
import CollectionCards from "./Modules/CollectionCards";
import Collections from "@/Store/Modules/Collections";

export default createStore({
    modules: {
        app,
        CollectionCards,
        Collections,
    },
});
