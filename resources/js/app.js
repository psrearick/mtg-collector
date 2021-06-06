require("./bootstrap");

import Guest from "@/Layouts/Guest";
import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";

createInertiaApp({
    resolve: (name) => {
        const page = require(`./Pages/${name}`).default;
        page.layout = page.layout || Guest;
        return page;
    },
    setup({ el, app, props, plugin }) {
        createApp({ render: () => h(app, props) })
            .mixin({ methods: { route } })
            .use(plugin)
            .mount(el);
    },
});

InertiaProgress.init({ color: "#4B5563" });
