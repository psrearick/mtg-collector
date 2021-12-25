<template>
    <div>
        <form
            class="space-y-8 divide-y divide-gray-200"
            @submit.prevent="submitForm"
        >
            <div class="space-y-8 divide-y divide-gray-200">
                <div>
                    <div
                        class="
                            mt-6
                            grid grid-cols-1
                            gap-y-6 gap-x-4
                            sm:grid-cols-6
                        "
                    >
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Folder Details
                        </h3>

                        <div class="sm:col-span-6">
                            <ui-input
                                v-model="form.name"
                                label="Name"
                                field-id="name"
                                name="name"
                                type="string"
                                placeholder="Name your folder"
                                :required="true"
                            />
                        </div>

                        <div class="sm:col-span-6">
                            <ui-text-area
                                v-model="form.description"
                                name="description"
                                type="textarea"
                                label="Description"
                                :required="false"
                                placeholder="Write a few sentences about your folder"
                                class="mb-4"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="pt-5">
                <div class="flex space-x-4 justify-end">
                    <inertia-link :href="cancelLink">
                        <ui-button
                            type="button"
                            button-style="white"
                            text="Cancel"
                        />
                    </inertia-link>
                    <ui-button
                        type="button"
                        button-style="primary-dark"
                        text="Create Folder"
                        @click="submitForm"
                    />
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import UiInput from "@/UI/Form/UIInput";
import UiButton from "@/UI/UIButton";
import UiTextArea from "@/UI/Form/UITextArea";

export default {
    name: "Create",

    components: { UiInput, UiButton, UiTextArea },

    layout: Layout,

    title: "MTG Collector - Create Folder",

    header: "Create Folder",

    props: {
        folder: {
            type: Number,
            default: null,
        },
    },

    data() {
        return {
            form: {
                name: "",
                description: "",
                folder: this.folder,
            },
        };
    },

    computed: {
        cancelLink() {
            if (this.folder) {
                return route("collection-folder.show", {
                    folder: this.folder,
                });
            }

            return route("collections.index");
        },
    },

    methods: {
        submitForm() {
            this.$inertia.post("/collections/collections/folders", {
                form: this.form,
            });
        },
    },
};
</script>
