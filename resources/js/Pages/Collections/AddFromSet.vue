<template>
    <div>
        <h3 class="text-lg leading-6 font-medium text-gray-900 py-4">
            Select a Set
        </h3>

        <SearchSelect
            v-model:selected="selectedSet"
            label="Select a Set"
            :options="allSets"
        />

        search results - card/set datagrid
    </div>
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import PrimaryButton from "@/Components/Buttons/PrimaryButton";
import SearchSelect from "@/Components/Form/SearchSelect/SearchSelect";

export default {
    name: "AddFromSet",
    components: { SearchSelect },
    layout: Layout,

    title: "MTG Collector - Add Set to Collection",

    props: {
        collection: {
            type: Object,
            default: () => {},
        },
        sets: {
            type: Array,
            default: () => {},
        },
        cards: {
            type: Object,
            default: () => {},
        },
    },

    data() {
        return {
            selectedSet: null,
            allSets: {},
        };
    },

    mounted() {
        this.$store.dispatch("updateHeader", {
            header: "Edit: " + this.collection.name,
        });
        this.$store.dispatch("updateSubheader", {
            subheader: this.collection.description,
        });
        this.$store.dispatch("updateHeaderRightComponent", {
            component: {
                is: PrimaryButton,
                props: {
                    text: "Done Editing",
                    href: route("collections.show", {
                        collection: this.collection.id,
                    }),
                },
            },
        });
        this.allSets = this.sets.map((set) => {
            return {
                primary: set.name,
                secondary: set.code,
                id: set.id,
            };
        });
    },
};
</script>
