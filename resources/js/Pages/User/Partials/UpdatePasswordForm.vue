<template>
    <ui-form-section @submitted="updatePassword">
        <template #title> Update Password </template>

        <template #description>
            Ensure your account is using a long, random password to stay secure.
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <ui-input
                    ref="current_password"
                    v-model="form.current_password"
                    label="Current Password"
                    name="current_password"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="current-password"
                    :error-message="form.errors.current_password"
                />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <ui-input
                    ref="password"
                    v-model="form.password"
                    name="password"
                    label="New Password"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                    :error-message="form.errors.password"
                />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <ui-input
                    v-model="form.password_confirmation"
                    label="Confirm Password"
                    name="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                    :error-message="form.errors.password_confirmation"
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
    name: "UpdatePasswordForm",

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
                current_password: "",
                password: "",
                password_confirmation: "",
                id: this.user.id,
            }),
        };
    },

    methods: {
        updatePassword() {
            this.form.put(route("user-password.update"), {
                errorBag: "updatePassword",
                preserveScroll: true,
                onSuccess: () => this.form.reset(),
                onError: () => {
                    if (this.form.errors.password) {
                        this.form.reset("password", "password_confirmation");
                        this.$refs.password.focus();
                    }

                    if (this.form.errors.current_password) {
                        this.form.reset("current_password");
                        this.$refs.current_password.focus();
                    }
                },
            });
        },
    },
};
</script>
