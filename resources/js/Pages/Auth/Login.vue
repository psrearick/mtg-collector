<template>
    <breeze-validation-errors class="mb-4" />

    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
        {{ status }}
    </div>

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

        <div class="mt-4">
            <breeze-label for="password" value="Password" />
            <breeze-input
                id="password"
                v-model="form.password"
                type="password"
                class="mt-1 block w-full"
                required
                autocomplete="current-password"
            />
        </div>

        <div class="block mt-4">
            <label class="flex items-center">
                <breeze-checkbox
                    v-model:checked="form.remember"
                    name="remember"
                />
                <span class="ml-2 text-sm text-gray-600">Remember me</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <inertia-link
                v-if="canResetPassword"
                :href="route('password.request')"
                class="underline text-sm text-gray-600 hover:text-gray-900"
            >
                Forgot your password?
            </inertia-link>

            <breeze-button
                class="ml-4"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Log in
            </breeze-button>
        </div>
    </form>
</template>

<script>
import BreezeButton from "@/Components/Buttons/Button";
import BreezeInput from "@/Components/Input";
import BreezeCheckbox from "@/Components/Checkbox";
import BreezeLabel from "@/Components/Label";
import BreezeValidationErrors from "@/Components/ValidationErrors";

export default {
    components: {
        BreezeButton,
        BreezeInput,
        BreezeCheckbox,
        BreezeLabel,
        BreezeValidationErrors,
    },

    props: {
        auth: { type: Object, default: () => {} },
        canResetPassword: { type: Boolean, default: false },
        errors: { type: Object, default: () => {} },
        status: { type: String, default: "" },
    },

    data() {
        return {
            form: this.$inertia.form({
                email: "",
                password: "",
                remember: false,
            }),
        };
    },

    methods: {
        submit() {
            this.form.post(this.route("login"), {
                onFinish: () => this.form.reset("password"),
            });
        },
    },
};
</script>
