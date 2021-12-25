<template>
    <ui-form-section @submitted="updateProfileInformation">
        <template #title> Profile Information </template>

        <template #description>
            Update your account's profile information and email address.
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <ui-input
                    v-model="form.name"
                    label="Name"
                    name="name"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="name"
                    :error-message="form.errors.name"
                />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <ui-input
                    v-model="form.email"
                    label="Email"
                    name="email"
                    type="email"
                    class="mt-1 block w-full"
                    :error-message="form.errors.email"
                />
            </div>
        </template>

        <template #actions>
            <ui-action-message :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </ui-action-message>

            <ui-button
                type="submit"
                button-style="primary-outline"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Save
            </ui-button>
        </template>
    </ui-form-section>
</template>

<script>
import UiButton from "@/UI/UIButton";
import UiFormSection from "@/UI/Form/UIFormSection";
import UiInput from "@/UI/Form/UIInput.vue";
import UiActionMessage from "@/UI/UIActionMessage";

export default {
    name: "UpdateProfileInformationForm",

    components: {
        UiActionMessage,
        UiButton,
        UiFormSection,
        UiInput,
    },

    props: {
        user: {
            type: Object,
            default: () => {},
        },
    },

    data() {
        return {
            form: this.$inertia.form({
                _method: "PUT",
                name: this.user.name,
                email: this.user.email,
                id: this.user.id,
            }),
        };
    },

    methods: {
        updateProfileInformation() {
            this.form.post(route("user-profile-information.update"), {
                errorBag: "updateProfileInformation",
                preserveScroll: true,
            });
        },
    },
};
</script>
