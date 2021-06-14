import { createStore } from "vuex";
import app from "./Modules/app";

export default createStore({
    modules: {
        app,
    },
});
