<template>
    <div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <div>
                    <img
                        :src="card.image_url"
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
                                v-for="(price, finish) in card.allFinishPrices"
                                :key="finish"
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
                                    {{ finish }}
                                </dt>
                                <dd
                                    class="
                                        mt-1
                                        text-3xl
                                        font-semibold
                                        text-gray-900
                                    "
                                >
                                    {{ format(price) }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Legalities
                        </h3>
                        <CardList class="pt-4" max-cols="2">
                            <CardListCard
                                v-for="(legality, index) in card.legalities"
                                :key="index"
                                :status="status(legality.status)"
                            >
                                <p class="text-sm font-medium text-gray-900">
                                    {{
                                        legality.status
                                            .replace(/_/g, " ")
                                            .toUpperCase()
                                    }}
                                </p>
                                <p class="text-sm text-gray-500 truncate">
                                    {{ legality.format.toLowerCase() }}
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
                                {{ capitalize(card.rarity.toLowerCase()) }}
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
                                Mana Cost
                            </dt>
                            <dd
                                class="
                                    mt-1
                                    text-sm text-gray-900
                                    sm:mt-0
                                    sm:col-span-2
                                "
                            >
                                <span v-if="card.manaCost" v-html="manaCost" />
                                (CMC: {{ card.convertedManaCost }})
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
                                    text-sm text-gray-900 text-center
                                    sm:mt-0
                                    sm:col-span-2
                                    flex
                                    md:block
                                    justify-start
                                "
                            >
                                <img
                                    :src="card.set_image_url"
                                    :alt="card.set.name"
                                    class="w-12 md:mb-4 mr-4 md:mx-auto"
                                />
                                {{ card.set.name }}
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
                                {{ card.typeLine }}
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
                                v-html="text"
                            />
                        </div>
                        <div
                            v-if="card.flavorText"
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
                                {{ card.languageCode.toUpperCase() }}
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
                Other Printings
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
import {
    formatCurrency,
    capitalizeFirstLetter,
    replaceSymbol,
} from "@/Shared/api/ConvertValue";
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
                        label: "Nonfoil",
                        key: "nonfoil",
                        sortable: false,
                        filterable: false,
                    },
                    {
                        visible: true,
                        type: "currency",
                        link: false,
                        label: "Foil",
                        key: "foil",
                        sortable: false,
                        filterable: false,
                    },
                    {
                        visible: true,
                        type: "currency",
                        link: false,
                        label: "Etched",
                        key: "etched",
                        sortable: false,
                        filterable: false,
                    },
                ],
            },
            text: "",
            manaCost: "",
        };
    },

    computed: {
        keywordList() {
            return _.join(
                _.map(this.card.keywords, (keyword) => {
                    return keyword.name;
                }),
                ", "
            );
        },
    },

    mounted() {
        this.$store.dispatch("updateHeader", { header: this.card.name });
        const allPrintings = [];
        Object.keys(this.card.printings).forEach((key) => {
            let printing = this.card.printings[key];
            allPrintings.push({
                id: printing.id,
                nonfoil: printing.allPrices["nonfoil"] || 0,
                foil: printing.allPrices["foil"] || 0,
                etched: printing.allPrices["etched"] || 0,
                rarity: printing.rarity,
                set_name: printing.set.name,
                release_date: printing.set.releaseDate,
                feature: printing.feature,
            });
        });
        this.printingsTable.data = allPrintings.sort((first, second) => {
            if (new Date(first.releaseDate) < new Date(second.releaseDate)) {
                return -1;
            }
            return 1;
        });
    },

    created() {
        this.emitter.on("printings_table_set_name_click", (card) => {
            this.$inertia.get(`/cards/cards/${card.id}`);
        });
        this.getTextWithSymbols(this.card.oracleText).then(
            (res) => (this.text = res)
        );
        this.getTextWithSymbols(this.card.manaCost).then(
            (res) => (this.manaCost = res)
        );
    },

    methods: {
        format(value) {
            return value ? formatCurrency(value) : "N/A";
        },
        capitalize(value) {
            return capitalizeFirstLetter(value);
        },
        async getTextWithSymbols(value) {
            if (!value) {
                return "";
            }
            return await replaceSymbol(value);
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
