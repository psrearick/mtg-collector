<template>
    <div class="flex space-x-4">
        <inertia-link :href="route('collection-folder.create', payload)">
            <ui-button text="Create Folder" button-style="primary-dark" />
        </inertia-link>

        <inertia-link :href="route('collections.create', payload)">
            <ui-button text="Create Collection" button-style="primary-dark" />
        </inertia-link>
    </div>
</template>
<script>
import UiButton from "@/UI/UIButton.vue";
export default {
    name: "CollectionIndexHeaderRight",

    components: { UiButton },

    props: {
        folder: {
            type: Number,
            default: null,
        },
    },

    computed: {
        payload() {
            return this.folder ? { folder: this.folder } : {};
        },
    },
};
</script>
