<template>
    <div>
        <h3 class="text-lg leading-6 font-medium text-gray-900 py-4">
            Select a Set
        </h3>

        <SearchSelect
            v-model:selected="selectedSet"
            label="Select a Set"
            :options="allSets"
            :search-term="searchTerm"
            :searching="searching"
            @update:selectedOption="queryCards"
            @update:searchTerm="querySets"
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
        setSets: {
            type: Array,
            default: () => {},
        },
        setCards: {
            type: Object,
            default: () => {},
        },
        queryString: {
            type: String,
            default: "",
        },
    },

    data() {
        return {
            selectedSet: null,
            allSets: [],
            searchTerm: "",
            searching: false,
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
        this.mapSets();
    },

    methods: {
        queryCards: function (set) {
            if (set < 0) {
                return;
            }
            this.$inertia.reload({
                data: {
                    set: set,
                },
                only: ["setCards"],
                onSuccess: (res) => {
                    console.log(res);
                },
            });
        },
        querySets: _.debounce(function (term) {
            this.searching = true;
            this.allSets = [];
            this.$inertia.reload({
                data: {
                    query: term,
                },
                only: ["setSets", "queryString"],
                onSuccess: () => {
                    this.searching = false;
                    this.mapSets();
                },
            });
        }, 1200),
        mapSets: function () {
            this.searchTerm = this.queryString;
            this.allSets = this.setSets.map((set) => {
                return {
                    primary: set.name,
                    secondary: set.code,
                    id: set.id,
                };
            });
        },
    },
};
</script>
