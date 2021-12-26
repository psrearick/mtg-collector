<template>
    <ui-modal v-if="collection.id" :show="show" title="Public Collection Link">
        <div v-if="collection.is_public">
            <p class="text-sm text-gray-500 mb-4">
                Below is the link for the following collection.
            </p>
            <p class="text-sm text-gray-500 font-bold mb-8">
                {{ collection.name }}
            </p>
            <p class="text-sm text-success-500">
                {{ route("public-collections.show", collection.id) }}
            </p>
        </div>
        <div v-else>
            <p class="text-sm text-gray-500">
                This collection is not public. Edit the collection to make it
                public.
            </p>
        </div>
        <template #footer>
            <div class="sm:flex sm:flex-row-reverse gap-2">
                <ui-button
                    text="Close"
                    button-style="primary-outline"
                    @click="$emit('update:show', false)"
                />
            </div>
        </template>
    </ui-modal>
</template>

<script>
import UiModal from "@/UI/UIModal";
import UiButton from "@/UI/UIButton";
export default {
    name: "PublicLinkModal",

    components: { UiButton, UiModal },

    props: {
        show: {
            type: Boolean,
            default: false,
        },
        collection: {
            type: Object,
            default: () => {},
        },
    },

    emits: ["update:show", "delete"],
};
</script>
