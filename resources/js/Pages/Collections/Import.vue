<template>
    <div>
        <div
            v-for="(card, index) in cards"
            :key="index"
            :class="
                'p-4 rounded-lg my-4 border border-gray-500 ' +
                getBackgroundColor(index)
            "
        >
            <div class="grid grid-cols-6 gap-x-4">
                <div>
                    <p class="text-gray-500 text-sm">
                        <span class="font-bold">Card: </span>
                        {{ card.import.name }}
                    </p>
                    <p class="text-gray-500 text-xs">
                        <span class="font-bold">Set: </span>
                        {{ card.import.printing }}
                    </p>
                    <p class="text-gray-500 text-xs">
                        <span class="font-bold">Foil: </span>
                        {{ card.import.foil ? "True" : "False" }}
                    </p>
                    <p class="text-gray-500 text-xs">
                        <span class="font-bold">Quantity: </span
                        >{{ card.import.quantity }}
                    </p>
                </div>
                <div class="col-span-3">
                    <span class="text-gray-500 text-sm font-bold"> Card </span>
                    <ui-select-menu
                        v-if="form.cards[index]"
                        v-model:show="showCardSelectMenu[index]"
                        v-model:selected="form.cards[index].name"
                        :name="'card-select-' + index"
                        :options="cardSelectOptions[index]"
                        @change="updateFinishOptions(card, index)"
                    />
                </div>
                <div>
                    <span class="text-gray-500 text-sm font-bold">
                        Finish
                    </span>
                    <ui-select-menu
                        v-if="form.cards[index] && finishSelectOptions[index]"
                        v-model:show="showFinishSelectMenu[index]"
                        v-model:selected="form.cards[index].finish"
                        :name="'card-select-' + index"
                        :options="finishSelectOptions[index]"
                    />
                </div>
                <div>
                    <span class="text-gray-500 text-sm font-bold">
                        Quantity
                    </span>
                    <ui-input
                        v-if="form.cards[index]"
                        v-model="form.cards[index].quantity"
                        type="number"
                        :step="1"
                    />
                </div>
            </div>
        </div>
        <div class="flex flex-row-reverse">
            <ui-button
                text="Import"
                button-style="primary-outline"
                @click="save()"
            />
        </div>
    </div>
</template>
<script>
import Layout from "@/Layouts/Authenticated";
import UiInput from "@/UI/Form/UIInput";
import UiSelectMenu from "@/UI/Form/UISelectMenu";
import UiButton from "@/UI/UIButton";

export default {
    name: "Import",

    title: "MTGCollector - Import",

    header: "Import",

    components: { UiInput, UiSelectMenu, UiButton },

    layout: Layout,

    props: {
        cards: {
            type: Object,
            default: () => {},
        },
        collection: {
            type: Number,
            default: null,
        },
    },

    data() {
        return {
            showCardSelectMenu: {},
            showFinishSelectMenu: {},
            cardSelectOptions: {},
            finishSelectOptions: {},
            form: {
                cards: {},
            },
        };
    },

    mounted() {
        let self = this;
        _.each(this.cards, (card, index) => {
            self.showCardSelectMenu[index] = false;
            self.showFinishSelectMenu[index] = false;
            let option = this.getCardSelectOption(card);
            self.cardSelectOptions[index] = option.options;
            self.form.cards[index] = {
                card: card,
                quantity: card.import.quantity.toString(),
                name: option.default,
                finish: null,
            };
            this.updateFinishOptions(card, index);
        });
    },

    methods: {
        getBackgroundColor(index) {
            let card = this.form.cards[index];
            if (!card) {
                return "bg-danger-100";
            }

            if (card.finish && card.name && card.quantity) {
                return "bg-gray-50";
            }

            return "bg-danger-100";
        },
        getCardSelectOption(card) {
            let resultCard = card.results[0];
            let options = [
                {
                    id: resultCard.card.id,
                    label:
                        resultCard.card.name +
                        (resultCard.card.feature
                            ? ` (${resultCard.card.feature})`
                            : "") +
                        (resultCard.card.set
                            ? " - " +
                              resultCard.card.set.name +
                              " (" +
                              resultCard.card.set.code.toUpperCase() +
                              ")"
                            : ""),
                    finishes: resultCard.card.finishes,
                },
            ];
            Object.values(resultCard.otherPrintings).forEach((printing) => {
                options.push({
                    id: printing.id,
                    label:
                        printing.name +
                        (printing.feature ? ` (${printing.feature})` : "") +
                        (printing.set
                            ? " - " +
                              printing.set.name +
                              " (" +
                              printing.set.code.toUpperCase() +
                              ")"
                            : ""),
                    finishes: printing.finishes,
                });
            });
            return {
                options: options,
                default: resultCard.card.id,
            };
        },
        updateFinishOptions(card, index) {
            let selectCardOptions = this.getCardSelectOption(card);
            let selected = selectCardOptions.options.find(
                (option) => option.id === this.form.cards[index].name
            );
            let map = _.map(selected.finishes, (finish) => {
                return {
                    id: finish.name,
                    label: finish.name,
                };
            });
            this.finishSelectOptions[index] = map;

            let defaultOption = null;
            if (card.import.foil) {
                let finish = map.find(
                    (finishType) => finishType.label === "foil"
                );
                if (finish) {
                    defaultOption = finish.id;
                }
            } else {
                let finish = map.find(
                    (finishType) => finishType.label === "nonfoil"
                );
                if (finish) {
                    defaultOption = finish.id;
                }
            }

            if (defaultOption) {
                this.form.cards[index].finish = defaultOption;
            }
        },
        save() {
            this.$inertia.visit(
                `/collections/collections/import/${this.collection}`,
                {
                    method: "patch",
                    data: this.form,
                    preserveState: true,
                }
            );
        },
    },
};
</script>
