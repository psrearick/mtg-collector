<template>
    <div
        v-show="show"
        class="fixed z-10 inset-0 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
    >
        <div
            class="
                flex
                items-end
                justify-center
                min-h-screen
                pt-4
                px-4
                pb-20
                text-center
                sm:block
                sm:p-0
            "
        >
            <Transition
                enter="ease-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div
                    class="
                        fixed
                        inset-0
                        bg-gray-500 bg-opacity-75
                        transition-opacity
                    "
                    aria-hidden="true"
                ></div>
            </Transition>

            <span
                class="hidden sm:inline-block sm:align-middle sm:h-screen"
                aria-hidden="true"
                >&#8203;</span
            >

            <Transition
                enter="ease-out duration-300"
                enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                enter-to="opacity-100 translate-y-0 sm:scale-100"
                leave="ease-in duration-200"
                leave-from="opacity-100 translate-y-0 sm:scale-100"
                leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
                <div
                    class="
                        inline-block
                        align-bottom
                        bg-white
                        rounded-lg
                        px-4
                        pt-5
                        pb-4
                        text-left
                        overflow-hidden
                        shadow-xl
                        transform
                        transition-all
                        sm:my-8
                        sm:align-middle
                        sm:max-w-lg
                        sm:w-full
                        sm:p-6
                    "
                >
                    <div class="sm:flex sm:items-start">
                        <div
                            v-if="hasIcon"
                            :class="
                                `
                                mx-auto
                                flex-shrink-0 flex
                                items-center
                                justify-center
                                h-12
                                w-12
                                rounded-full
                                sm:mx-0
                                sm:h-10
                                sm:w-10
                            ` + iconBackground
                            "
                        >
                            <slot name="icon" />
                        </div>
                        <div
                            class="
                                mt-3
                                text-center
                                sm:mt-0
                                sm:ml-4
                                sm:text-left
                            "
                        >
                            <h3
                                id="modal-title"
                                class="
                                    text-lg
                                    leading-6
                                    font-medium
                                    text-gray-900
                                "
                            >
                                {{ title }}
                            </h3>
                            <div class="mt-2">
                                <slot></slot>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4">
                        <slot name="footer" />
                    </div>
                </div>
            </Transition>
        </div>
    </div>
</template>

<script>
export default {
    name: "UiModal",

    props: {
        show: {
            type: Boolean,
            default: false,
        },
        title: {
            type: String,
            default: "",
        },
        hasIcon: {
            type: Boolean,
            default: false,
        },
        iconBackground: {
            type: String,
            default: "bg-red-100",
        },
    },
};
</script>
