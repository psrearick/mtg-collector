<template>
    <div>
        <transition
            enter-active-class="transform transition ease-in-out duration-500 sm:duration-700"
            enter-from-class="translate-x-full"
            enter-to-class="translate-x-0"
            leave-active-class="transform transition ease-in-out duration-500 sm:duration-700"
            leave-from-class="translate-x-0"
            leave-to-class="translate-x-full"
        >
            <section
                v-show="show"
                class="fixed inset-0 overflow-hidden"
                aria-labelledby="slide-over-title"
                role="dialog"
                aria-modal="true"
            >
                <div class="absolute inset-0 overflow-hidden">
                    <div class="absolute inset-0" aria-hidden="true" />

                    <div
                        class="
                            absolute
                            inset-y-0
                            right-0
                            md:pl-10
                            max-w-full
                            flex
                        "
                    >
                        <div :class="`w-screen ${panelWidthClass}`">
                            <div
                                class="
                                    h-full
                                    divide-y divide-gray-200
                                    flex flex-col
                                    bg-white
                                    shadow-xl
                                "
                            >
                                <div
                                    class="
                                        min-h-0
                                        flex-1 flex flex-col
                                        py-6
                                        overflow-y-scroll
                                    "
                                >
                                    <div class="px-4 sm:px-6">
                                        <div
                                            class="
                                                flex
                                                items-start
                                                justify-between
                                            "
                                        >
                                            <h2
                                                id="slide-over-title"
                                                class="
                                                    text-lg
                                                    font-medium
                                                    text-gray-900
                                                "
                                            >
                                                {{ title }}
                                            </h2>
                                            <div
                                                class="
                                                    ml-3
                                                    h-7
                                                    flex
                                                    items-center
                                                "
                                            >
                                                <button
                                                    class="
                                                        bg-white
                                                        rounded-md
                                                        text-gray-400
                                                        hover:text-gray-500
                                                        focus:outline-none
                                                        focus:ring-2
                                                        focus:ring-primary-500
                                                    "
                                                    @click="close"
                                                >
                                                    <span class="sr-only"
                                                        >Close panel</span
                                                    >
                                                    <svg
                                                        class="h-6 w-6"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M6 18L18 6M6 6l12 12"
                                                        />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="
                                            mt-6
                                            relative
                                            flex-1
                                            px-4
                                            sm:px-6
                                        "
                                    >
                                        <div class="h-full">
                                            <slot />
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="
                                        flex-shrink-0
                                        px-4
                                        py-4
                                        flex
                                        justify-between
                                    "
                                >
                                    <div>
                                        <UIButton
                                            :text="closeText"
                                            button-style="white"
                                            @click="close"
                                        />
                                        <UIButton
                                            v-if="form && clear"
                                            :text="clearText"
                                            class="ml-2"
                                            button-style="secondary"
                                            @click="$emit('clearForm')"
                                        />
                                    </div>
                                    <div>
                                        <UIButton
                                            v-if="form"
                                            :text="saveText"
                                            :button-style="saveButtonStyle"
                                            @click="save"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </transition>
    </div>
</template>

<script>
import UIButton from "@/UI/UIButton";

export default {
    name: "UiPanel",

    components: {
        UIButton,
    },

    props: {
        show: {
            type: Boolean,
            default: false,
        },
        title: {
            type: String,
            default: "",
        },
        saveButtonStyle: {
            type: String,
            default: "primary-outline",
        },
        form: {
            type: Boolean,
            default: true,
        },
        clear: {
            type: Boolean,
            default: true,
        },
        clearText: {
            type: String,
            default: "Clear",
        },
        closeText: {
            type: String,
            default: "Cancel",
        },
        saveText: {
            type: String,
            default: "Save",
        },
        wide: {
            type: Boolean,
            default: false,
        },
    },

    emits: ["update:show", "clearForm", "close", "save"],

    computed: {
        panelWidthClass: function () {
            return this.wide ? "md:max-w-3xl" : "md:max-w-md";
        },
    },

    methods: {
        showPanel(show) {
            this.$emit("update:show", show);
        },
        close() {
            this.$emit("close");
            this.showPanel(false);
        },
        save() {
            this.$emit("save");
        },
    },
};
</script>
