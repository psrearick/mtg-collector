<template>
    <div class="flex gap-4">
        <ui-button
            button-style="primary-dark"
            text="Import into Collection"
            @click="importCollection"
        />
        <inertia-link :href="editLink">
            <ui-button button-style="primary-dark" text="Edit Collection" />
        </inertia-link>
    </div>
</template>
<script>
import UiButton from "@/UI/UIButton";
export default {
    name: "CollectionShowHeaderRight",

    components: { UiButton },

    props: {
        collection: {
            type: Object,
            default: () => {},
        },
    },

    computed: {
        editLink() {
            return `/collections/collections/${this.collection.id}/edit`;
        },
    },

    methods: {
        importCollection() {
            this.emitter.emit("import-collection", this.collection);
        },
    },
};
</script>
