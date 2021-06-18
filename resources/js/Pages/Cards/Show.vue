<template>
    <div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <div>
                    <img
                        :src="scryfallCard['image_uris']['normal']"
                        :alt="card.name"
                        class="max-w-xs mb-8 mx-auto"
                    />
                    <p
                        class="
                            text-sm
                            font-medium
                            text-gray-500
                            truncate
                            text-center
                        "
                    >
                        {{ card.set.name }}
                    </p>
                </div>
            </div>
            <div class="px-4">
                <div>
                    <div class="mb-8">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Current Prices
                        </h3>
                        <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
                            <div
                                class="
                                    px-4
                                    py-5
                                    bg-white
                                    shadow
                                    rounded-lg
                                    overflow-hidden
                                    sm:p-6
                                "
                            >
                                <dt
                                    class="
                                        text-sm
                                        font-medium
                                        text-gray-500
                                        truncate
                                    "
                                >
                                    Non-Foil
                                </dt>
                                <dd
                                    class="
                                        mt-1
                                        text-3xl
                                        font-semibold
                                        text-gray-900
                                    "
                                >
                                    {{ format(card.price_normal) }}
                                </dd>
                            </div>

                            <div
                                class="
                                    px-4
                                    py-5
                                    bg-white
                                    shadow
                                    rounded-lg
                                    overflow-hidden
                                    sm:p-6
                                "
                            >
                                <dt
                                    class="
                                        text-sm
                                        font-medium
                                        text-gray-500
                                        truncate
                                    "
                                >
                                    Foil
                                </dt>
                                <dd
                                    class="
                                        mt-1
                                        text-3xl
                                        font-semibold
                                        text-gray-900
                                    "
                                >
                                    {{ format(card.price_foil) }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Legalities
                        </h3>
                        <CardList class="pt-4">
                            <CardListCard
                                v-for="(
                                    legality, index
                                ) in scryfallCard.legalities"
                                :key="index"
                                :status="status(legality)"
                            >
                                <p class="text-sm font-medium text-gray-900">
                                    {{
                                        index.charAt(0).toUpperCase() +
                                        index.slice(1)
                                    }}
                                </p>
                                <p class="text-sm text-gray-500 truncate">
                                    {{ legality.replace(/_/g, " ") }}
                                </p>
                            </CardListCard>
                        </CardList>
                    </div>
                </div>
            </div>
            <div
                class="
                    bg-white
                    shadow
                    overflow-hidden
                    sm:rounded-lg
                    md:col-span-2
                    lg:col-span-1
                    mt-8
                    lg:mt-0
                "
            >
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Card Details
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Information about {{ card.name }}
                        <span v-if="card.feature">({{ card.feature }})</span>
                    </p>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                    <dl class="sm:divide-y sm:divide-gray-200">
                        <div
                            class="
                                py-4
                                sm:py-5
                                sm:grid sm:grid-cols-3
                                sm:gap-4
                                sm:px-6
                            "
                        >
                            <dt class="text-sm font-medium text-gray-500">
                                Rarity
                            </dt>
                            <dd
                                class="
                                    mt-1
                                    text-sm text-gray-900
                                    sm:mt-0
                                    sm:col-span-2
                                "
                            >
                                {{ card.rarity }}
                            </dd>
                        </div>
                        <div
                            class="
                                py-4
                                sm:py-5
                                sm:grid sm:grid-cols-3
                                sm:gap-4
                                sm:px-6
                            "
                        >
                            <dt class="text-sm font-medium text-gray-500">
                                CMC
                            </dt>
                            <dd
                                class="
                                    mt-1
                                    text-sm text-gray-900
                                    sm:mt-0
                                    sm:col-span-2
                                "
                            >
                                {{ card.convertedManaCost }}
                                <span v-if="card.manaCost">
                                    {{ " - " + card.manaCost }}</span
                                >
                            </dd>
                        </div>
                        <div
                            class="
                                py-4
                                sm:py-5
                                sm:grid sm:grid-cols-3
                                sm:gap-4
                                sm:px-6
                            "
                        >
                            <dt class="text-sm font-medium text-gray-500">
                                Set
                            </dt>
                            <dd
                                class="
                                    mt-1
                                    text-sm text-gray-900
                                    sm:mt-0
                                    sm:col-span-2
                                "
                            >
                                {{ set.name }}
                            </dd>
                        </div>
                        <div
                            class="
                                py-4
                                sm:py-5
                                sm:grid sm:grid-cols-3
                                sm:gap-4
                                sm:px-6
                            "
                        >
                            <dt class="text-sm font-medium text-gray-500">
                                Type
                            </dt>
                            <dd
                                class="
                                    mt-1
                                    text-sm text-gray-900
                                    sm:mt-0
                                    sm:col-span-2
                                "
                            >
                                {{ typeLine }}
                            </dd>
                        </div>
                        <div
                            class="
                                py-4
                                sm:py-5
                                sm:grid sm:grid-cols-3
                                sm:gap-4
                                sm:px-6
                            "
                        >
                            <dt class="text-sm font-medium text-gray-500">
                                Keywords
                            </dt>
                            <dd
                                class="
                                    mt-1
                                    text-sm text-gray-900
                                    sm:mt-0
                                    sm:col-span-2
                                "
                            >
                                {{ keywordList }}
                            </dd>
                        </div>
                        <div
                            class="
                                py-4
                                sm:py-5
                                sm:grid sm:grid-cols-3
                                sm:gap-4
                                sm:px-6
                            "
                        >
                            <dt class="text-sm font-medium text-gray-500">
                                Text
                            </dt>
                            <dd
                                class="
                                    mt-1
                                    text-sm text-gray-900
                                    sm:mt-0
                                    sm:col-span-2
                                "
                                v-html="card.text"
                            />
                        </div>
                        <div
                            class="
                                py-4
                                sm:py-5
                                sm:grid sm:grid-cols-3
                                sm:gap-4
                                sm:px-6
                            "
                        >
                            <dt class="text-sm font-medium text-gray-500">
                                Flavor Text
                            </dt>
                            <dd
                                class="
                                    mt-1
                                    text-sm text-gray-900
                                    sm:mt-0
                                    sm:col-span-2
                                "
                                v-html="card.flavorText"
                            />
                        </div>
                        <div
                            class="
                                py-4
                                sm:py-5
                                sm:grid sm:grid-cols-3
                                sm:gap-4
                                sm:px-6
                            "
                        >
                            <dt class="text-sm font-medium text-gray-500">
                                Artist
                            </dt>
                            <dd
                                class="
                                    mt-1
                                    text-sm text-gray-900
                                    sm:mt-0
                                    sm:col-span-2
                                "
                            >
                                {{ card.artist }}
                            </dd>
                        </div>
                        <div
                            class="
                                py-4
                                sm:py-5
                                sm:grid sm:grid-cols-3
                                sm:gap-4
                                sm:px-6
                            "
                        >
                            <dt class="text-sm font-medium text-gray-500">
                                Language
                            </dt>
                            <dd
                                class="
                                    mt-1
                                    text-sm text-gray-900
                                    sm:mt-0
                                    sm:col-span-2
                                "
                            >
                                {{ scryfallCard.lang }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
        <div>
            <h3
                class="text-lg leading-6 font-medium text-gray-900 mt-8 lg:mt-0"
            >
                All Printings
            </h3>
            <DataGrid
                :data="printingsTable.data"
                :fields="printingsTable.fields"
                :show-search="false"
                :show-pagination="false"
            />
        </div>
    </div>
</template>

<script>
import Layout from "@/Layouts/Authenticated";
import { formatCurrency } from "@/Shared/api/ConvertValue";
import CardList from "@/Components/CardLists/CardList";
import CardListCard from "@/Components/CardLists/CardListCard";
import DataGrid from "@/Components/DataGrid/DataGrid";

export default {
    name: "Show",
    components: { DataGrid, CardListCard, CardList },
    layout: Layout,

    header: "",

    props: {
        card: {
            type: Object,
            default: () => {},
        },
        set: {
            type: Object,
            default: () => {},
        },
        colors: {
            type: Object,
            default: () => {},
        },
        keywords: {
            type: Object,
            default: () => {},
        },
        subtypes: {
            type: Object,
            default: () => {},
        },
        supertypes: {
            type: Object,
            default: () => {},
        },
        types: {
            type: Object,
            default: () => {},
        },
        faces: {
            type: Object,
            default: () => {},
        },
        frameEffects: {
            type: Object,
            default: () => {},
        },
        leadershipSkills: {
            type: Object,
            default: () => {},
        },
        legalities: {
            type: Object,
            default: () => {},
        },
        printings: {
            type: Object,
            default: () => {},
        },
        printingSets: {
            type: Object,
            default: () => {},
        },
        rulings: {
            type: Object,
            default: () => {},
        },
        tokens: {
            type: Object,
            default: () => {},
        },
        variations: {
            type: Object,
            default: () => {},
        },
        scryfallCard: {
            type: Object,
            default: () => {},
        },
    },

    data() {
        return {
            printingsTable: {
                data: [],
                fields: [
                    {
                        visible: true,
                        type: "text",
                        link: true,
                        hover: true,
                        label: "Set",
                        key: "set_name",
                        events: {
                            click: "printings_table_set_name_click",
                            hover: "printings_table_set_name_hover",
                        },
                        sortable: false,
                        filterable: false,
                    },
                    {
                        visible: true,
                        type: "text",
                        link: false,
                        label: "Rarity",
                        key: "rarity",
                        sortable: false,
                        filterable: false,
                    },
                    {
                        visible: true,
                        type: "text",
                        link: false,
                        label: "Features",
                        key: "feature",
                        sortable: false,
                        filterable: false,
                    },
                    {
                        visible: true,
                        type: "currency",
                        link: false,
                        label: "Non-Foil Price",
                        key: "non_foil_price",
                        sortable: false,
                        filterable: false,
                    },
                    {
                        visible: true,
                        type: "currency",
                        link: false,
                        label: "Foil Price",
                        key: "foil_price",
                        sortable: false,
                        filterable: false,
                    },
                ],
            },
        };
    },

    computed: {
        keywordList() {
            return _.join(
                _.map(this.keywords, (keyword) => {
                    return keyword.name;
                }),
                ", "
            );
        },
        typeLine() {
            let typeLine = "";
            let subTypeLine = "";
            if (this.types && this.types.length) {
                this.types.forEach((type) => {
                    if (typeLine.length) {
                        typeLine += " ";
                    }
                    typeLine += type.name;
                });
            }
            if (this.subtypes && this.subtypes.length) {
                this.subtypes.forEach((subtype) => {
                    if (subTypeLine.length) {
                        subTypeLine += " ";
                    }
                    subTypeLine += subtype.name;
                });
                if (typeLine.length) {
                    subTypeLine = " - " + subTypeLine;
                }
            }
            return typeLine + subTypeLine;
        },
    },

    mounted() {
        this.$store.dispatch("updateHeader", { header: this.card.name });
        const allPrintings = [];
        this.printings.forEach((printing) => {
            allPrintings.push({
                id: printing.id,
                non_foil_price: printing.price_normal,
                foil_price: printing.price_foil,
                rarity: printing.rarity,
                set_name: printing.set.name,
                release_date: printing.set.releaseDate,
                feature: printing.feature,
            });
        });
        this.printingsTable.data = allPrintings.sort((first, second) => {
            if (new Date(first.release_date) < new Date(second.release_date)) {
                return -1;
            }
            return 1;
        });
    },

    created() {
        this.emitter.on("printings_table_set_name_click", (card) => {
            this.$inertia.get(`/cards/cards/${card.id}`);
        });
    },

    methods: {
        format(value) {
            return value ? formatCurrency(value) : "N/A";
        },
        status(value) {
            if (value === "banned") {
                return "danger";
            }

            if (value === "legal") {
                return "success";
            }

            return "warning";
        },
    },
};
</script>
