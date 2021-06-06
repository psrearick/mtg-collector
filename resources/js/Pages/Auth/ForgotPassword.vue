<template>
    <div class="mb-4 text-sm text-gray-600">
        Forgot your password? No problem. Just let us know your email address
        and we will email you a password reset link that will allow you to
        choose a new one.
    </div>

    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
        {{ status }}
    </div>

    <breeze-validation-errors class="mb-4" />

    <form @submit.prevent="submit">
        <div>
            <breeze-label for="email" value="Email" />
            <breeze-input
                id="email"
                v-model="form.email"
                type="email"
                class="mt-1 block w-full"
                required
                autofocus
                autocomplete="username"
            />
        </div>

        <div class="flex items-center justify-end mt-4">
            <Button
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Email Password Reset Link
            </Button>
        </div>
        <div class="flex items-center justify-end mt-2">
            <inertia-link
                href="login"
                class="underline text-sm text-gray-600 hover:text-gray-900"
            >
                Remembered you password?
            </inertia-link>
        </div>
    </form>
</template>

<script>
import Button from "@/Components/Buttons/Button";
import BreezeInput from "@/Components/Input";
import BreezeLabel from "@/Components/Label";
import BreezeValidationErrors from "@/Components/ValidationErrors";

export default {
    components: {
        Button,
        BreezeInput,
        BreezeLabel,
        BreezeValidationErrors,
    },

    props: {
        auth: { type: Object, default: () => {} },
        errors: { type: Object, default: () => {} },
        status: { type: String, default: "" },
    },

    data() {
        return {
            form: this.$inertia.form({
                email: "",
            }),
        };
    },

    methods: {
        submit() {
            this.form.post(this.route("password.email"));
        },
    },
};
</script>
