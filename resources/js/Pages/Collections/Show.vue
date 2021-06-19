<template>
    <div>
        <CollectionsShowCardList :summary="collection.summary" />
        <div>
            <CardIndexDataGrid
                v-model:card-term="cardSearchTerm"
                v-model:set-term="setSearchTerm"
                v-model:searching="searching"
                :data="collection.cards.data"
                :fields="table.fields"
                :show-pagination="true"
                :force-show="true"
                :show-search="true"
                :pagination="collection.cards"
            />
        </div>
    </div>
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import PrimaryButton from "@/Components/Buttons/PrimaryButton";
import CardIndexDataGrid from "@/Components/DataGrid/CardIndexDataGrid/CardIndexDataGrid";
import CollectionsShowCardList from "@/Components/CardLists/CollectionsShowCardList";
import CollectionsShowTable from "@/Shared/TableDefinitions/CollectionsShowTable";

export default {
    name: "ShowCollection",

    components: {
        CollectionsShowCardList,
        CardIndexDataGrid,
    },

    layout: Layout,

    title: "MTG Collector - Collection",

    props: {
        collection: {
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
            table: CollectionsShowTable,
        };
    },

    watch: {
        cardSearchTerm() {
            if (this.cardSearchTerm !== this.cardQuery && this.loaded) {
                this.search();
            }
        },
        setSearchTerm() {
            if (this.setSearchTerm !== this.setQuery && this.loaded) {
                this.search();
            }
        },
    },

    mounted() {
        this.$store.dispatch("updateHeader", { header: this.collection.name });
        this.$store.dispatch("updateSubheader", {
            subheader: this.collection.description,
        });
        this.$store.dispatch("updateHeaderRightComponent", {
            component: {
                is: PrimaryButton,
                props: {
                    text: "Edit Collection",
                    href: route("collections.edit", {
                        collection: this.collection.id,
                    }),
                },
            },
        });
    },

    created() {
        this.mount();
    },

    methods: {
        mount() {
            this.cardSearchTerm = this.collection.cardQuery;
            this.setSearchTerm = this.collection.setQuery;
            this.loaded = true;
        },
        search: _.debounce(function () {
            this.searching = true;
            this.$inertia.get(
                "/collections/collections/" + this.collection.id,
                {
                    cardSearch: this.cardSearchTerm,
                    setSearch: this.setSearchTerm,
                },
                {
                    onSuccess: () => {
                        this.searching = false;
                        this.mount();
                    },
                }
            );
        }, 1200),
    },
};
</script>

<style scoped></style>
