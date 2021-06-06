import HeaderMixin from "@/Shared/Mixins/HeaderMixin";

require("./bootstrap");

import Guest from "@/Layouts/Guest";
import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
import mitt from "mitt";
import TitleMixin from "@/Shared/Mixins/TitleMixin";

const emitter = mitt();
createInertiaApp({
    resolve: (name) => {
        const page = require(`./Pages/${name}`).default;
        page.layout = page.layout || Guest;
        return page;
    },
    setup({ el, app, props, plugin }) {
        const vueApp = createApp({ render: () => h(app, props) })
            .mixin({ methods: { route } })
            .mixin(TitleMixin)
            .mixin(HeaderMixin)
            .use(plugin);
        vueApp.config.globalProperties.emitter = emitter;
        vueApp.mount(el);
    },
});

InertiaProgress.init({ color: "#4B5563" });
