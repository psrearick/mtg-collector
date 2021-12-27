<template>
    <div>
        <ui-input-label :label="label" />
        <div>
            <label
                :for="name"
                class="block h-64 relative overflow-hidden rounded"
                @mouseenter="active = true"
                @mouseleave="active = false"
            >
                <input
                    :id="id"
                    :name="name"
                    type="file"
                    class="absolute top-0 left-0 right-0 bottom-0 w-full block"
                    @input="handleUpload"
                />
                <span
                    class="
                        bg-white
                        absolute
                        top-0
                        left-0
                        right-0
                        bottom-0
                        w-full
                        block
                        pointer-events-none
                        flex
                        justify-center
                        items-center
                        max-w-lg
                        flex
                        px-6
                        pt-5
                        pb-6
                        border-2 border-gray-300 border-dashed
                        rounded-md
                    "
                >
                    <div class="text-center">
                        <slot>
                            <div class="space-y-1 text-center">
                                <svg
                                    class="mx-auto h-12 w-12 text-gray-400"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 48 48"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                                <strong
                                    :class="
                                        active
                                            ? 'text-primary-800'
                                            : 'text-primary-500'
                                    "
                                >
                                    Upload File
                                </strong>
                                <div v-if="files.length === 0">
                                    <p>or drag and drop</p>
                                    <p class="text-xs text-gray-500">
                                        {{ types }}
                                    </p>
                                </div>
                            </div>
                        </slot>
                        <small
                            v-if="files.length"
                            :class="`text-gray-600 block`"
                        >
                            <slot
                                name="file"
                                :files="files"
                                :uploadInfo="uploadInfo"
                            >
                                {{ uploadInfo }}
                            </slot>
                        </small>
                    </div>
                </span>
            </label>
        </div>
    </div>
</template>
<script>
import UiInputLabel from "@/UI/Form/UIInputLabel";

export default {
    name: "UiFileUpload",

    components: { UiInputLabel },

    props: {
        modelValue: {
            type: File,
            default: null,
        },
        types: {
            type: String,
            default: "",
        },
        id: {
            type: String,
            default: "drag-and-drop-input",
        },
        name: {
            type: String,
            default: "",
        },
        label: {
            type: String,
            default: "",
        },
        multiple: {
            type: Boolean,
            default: false,
        },
    },

    emits: ["update:modelValue"],

    data() {
        return {
            files: [],
            active: false,
        };
    },

    computed: {
        uploadInfo() {
            return this.files.length === 1
                ? this.files[0].name
                : `${this.files.length} files selected`;
        },
    },

    methods: {
        handleUpload(event) {
            this.files = Array.from(event.target.files) || [];
            let values = this.multiple ? this.files : this.files[0];
            this.$emit("update:modelValue", values);
        },
    },
};
</script>
