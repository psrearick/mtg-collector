<template>
    <div>
        <div class="mb-12">
            <h3 class="text-lg leading-6 font-medium text-gray-900 py-4">
                Add Cards to Collection
            </h3>
            <div class="w-full">
                <CardSearch :collection="collectionComplete" :search="search" />
            </div>
        </div>
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 my-4">
                Edit Collection
            </h3>
            <div class="w-full">
                <CardIndexDataGrid
                    v-model:card-term="cardSearchTerm"
                    v-model:set-term="setSearchTerm"
                    v-model:searching="searching"
                    :data="filteredCollection.data"
                    :fields="table.fields"
                    :show-pagination="true"
                    :force-show="true"
                    :show-search="true"
                    :pagination="filteredCollection"
                />
            </div>
        </div>
    </div>
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import PrimaryButton from "@/Components/Buttons/PrimaryButton";
import CardSearch from "@/Components/Form/CardSearch/CardSearch";
import CardIndexDataGrid from "@/Components/DataGrid/CardIndexDataGrid/CardIndexDataGrid";
import CollectionsEditTable from "@/Shared/TableDefinitions/CollectionsEditTable";

export default {
    name: "EditCollection",

    components: { CardIndexDataGrid, CardSearch },

    mixins: [CollectionsEditTable],

    layout: Layout,

    title: "MTG Collector - Edit Collection",

    props: {
        collection: {
            type: Object,
            default: () => {},
        },
        collectionComplete: {
            type: Object,
            default: () => {},
        },
        search: {
            type: Object,
            default: () => {},
        },
    },

    data() {
        return {
            setSearchTerm: "",
            cardSearchTerm: "",
            loaded: false,
            searching: false,
            filteredCollection: {},
        };
    },

    watch: {
        cardSearchTerm() {
            if (this.cardSearchTerm !== this.cardQuery && this.loaded) {
                this.query();
            }
        },
        setSearchTerm() {
            if (this.setSearchTerm !== this.setQuery && this.loaded) {
                this.query();
            }
        },
        collection() {
            this.$store.dispatch("addFilteredCollection", {
                collection: this.collection.cards,
            });
            this.filteredCollection = this.$store.getters.filteredCollection;
        },
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
    },

    created() {
        this.mount();
        this.$store.dispatch("addFilteredCollection", {
            collection: this.collection.cards,
        });
    },

    methods: {
        mount() {
            this.cardSearchTerm = this.collection.cardQuery;
            this.setSearchTerm = this.collection.setQuery;
            this.loaded = true;
            this.$store.dispatch("addFilteredCollection", {
                collection: this.collection.cards,
            });
            this.filteredCollection = this.$store.getters.filteredCollection;
        },
        query: _.debounce(function () {
            this.searching = true;
            this.$inertia.reload({
                data: {
                    cardSearch: this.cardSearchTerm,
                    setSearch: this.setSearchTerm,
                },
                only: ["collection"],
                onSuccess: () => {
                    this.searching = false;
                    this.mount();
                },
            });
        }, 1200),
    },
};
</script>
