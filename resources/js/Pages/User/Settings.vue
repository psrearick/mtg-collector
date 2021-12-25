<template>
    <div class="py-12">
        <div class="max-w-7xl mx-auto md:px-6 lg:px-8">
            <div class="bg-white overflow-hidden sm:rounded-lg">
                <div class="md:p-6 bg-white border-b border-gray-200">
                    <update-profile-information-form
                        :user="$page.props.auth.user"
                    />
                </div>
                <div class="md:p-6 bg-white">
                    <update-password-form :user="$page.props.auth.user" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";

export default {
    components: { UpdateProfileInformationForm, UpdatePasswordForm },
    layout: Layout,

    title: "MTG Collector - Settings",

    header: "Settings",
};
</script>
