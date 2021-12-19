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
                            Collection Details
                        </h3>
                        <div class="sm:col-span-6">
                            <Input
                                v-model="form.name"
                                label="Name"
                                field-id="name"
                                field-name="name"
                                helper-text="Name your collection."
                                required
                            />
                        </div>

                        <div class="sm:col-span-6">
                            <TextArea
                                v-model="form.description"
                                label="Description"
                                field-id="description"
                                field-name="description"
                                helper-text="Write a few sentences about your collection."
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="pt-5">
                <div class="flex justify-end">
                    <inertia-link :href="route('collections.index')">
                        <WhiteButton type="button">Cancel</WhiteButton>
                    </inertia-link>
                    <primary-button
                        type="button"
                        class="ml-2"
                        @click="submitForm"
                        >Create Collection</primary-button
                    >
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import TextArea from "@/Components/Form/TextArea";
import Input from "@/Components/Form/Input";
import WhiteButton from "@/Components/Buttons/WhiteButton";
import PrimaryButton from "@/Components/Buttons/PrimaryButton";

export default {
    name: "Create",
    components: { PrimaryButton, WhiteButton, Input, TextArea },
    layout: Layout,

    title: "MTG Collector = Create Collection",

    header: "Create Collection",

    data() {
        return {
            form: {
                name: "",
                description: "",
            },
        };
    },

    methods: {
        submitForm() {
            this.$inertia.post("/collections/collections/", {
                form: this.form,
            });
        },
    },
};
</script>
