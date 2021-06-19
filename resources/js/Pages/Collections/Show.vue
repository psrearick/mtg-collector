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
                :pagination="pagination"
            />
        </div>
    </div>
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import PrimaryButton from "@/Components/Buttons/PrimaryButton";
import CardIndexDataGrid from "@/Components/DataGrid/CardIndexDataGrid/CardIndexDataGrid";
import CollectionsShowCardList from "@/Components/CardLists/CollectionsShowCardList";

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
            table: {
                fields: [
                    {
                        visible: true,
                        type: "composite-text",
                        link: true,
                        label: "Card",
                        values: [
                            {
                                key: "name",
                                classes: "",
                            },
                            {
                                key: "foil_formatted",
                                classes: "text-sm text-gray-500 pl-2",
                            },
                        ],
                        events: {
                            click: "collection_card_name_click",
                        },
                    },
                    {
                        visible: true,
                        type: "text",
                        link: false,
                        label: "Set",
                        key: "set",
                    },
                    {
                        visible: true,
                        type: "text",
                        label: "Features",
                        key: "features",
                    },
                    {
                        visible: true,
                        type: "currency",
                        label: "Today",
                        key: "today",
                    },
                    {
                        visible: true,
                        type: "text",
                        label: "Acquired Date",
                        key: "acquired_date",
                    },
                    {
                        visible: true,
                        type: "currency",
                        label: "Acquired Price",
                        key: "acquired_price",
                    },
                ],
            },
            pagination: {},
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

        this.pagination = {
            current_page: this.collection.cards.current_page,
            first_page_url: this.collection.cards.first_page_url,
            last_page: this.collection.cards.last_page,
            last_page_url: this.collection.cards.last_page_url,
            next_page_url: this.collection.cards.next_page_url,
            previous_page_url: this.collection.cards.previous_page_url,
            links: this.collection.cards.links,
            from: this.collection.cards.from,
            to: this.collection.cards.to,
            total: this.collection.cards.total,
        };
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
