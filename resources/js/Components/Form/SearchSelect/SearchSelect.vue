<template>
    <div>
        <label
            id="listbox-label"
            class="block text-sm font-medium text-gray-700"
        >
            {{ label }}
        </label>
        <div class="mt-1 relative">
            <SearchSelectField
                :active="active"
                :selected-option="selectedOption"
                :model-value="searchTerm"
                @update-active="updateActive"
                @update:model-value="search"
            />
            <p v-if="searching" class="text-xs text-gray-400">Searching...</p>
            <transition
                leave-active-class="transition ease-in duration-100"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <SearchSelectOptions v-if="active && options.length">
                    <SearchSelectOption
                        v-for="(option, index) in selectOptions"
                        :key="index"
                        :selected="index === selectedOptionIndex"
                        :option="option"
                        @select-option="updatedSelectedOption(index)"
                    />
                </SearchSelectOptions>
            </transition>
        </div>
    </div>
</template>

<script>
import Icon from "@/Components/Icon";
import SearchSelectOptions from "@/Components/Form/SearchSelect/SearchSelectOptions";
import SearchSelectOption from "@/Components/Form/SearchSelect/SearchSelectOption";
import SearchSelectField from "@/Components/Form/SearchSelect/SearchSelectField";
export default {
    name: "SearchSelect",

    components: {
        SearchSelectField,
        SearchSelectOption,
        SearchSelectOptions,
        Icon,
    },

    props: {
        label: {
            type: String,
            default: "",
        },
        options: {
            type: Array,
            default: () => {},
        },
        selected: {
            type: Number,
            default: -1,
        },
        searchTerm: {
            type: String,
            default: "",
        },
        searching: {
            type: Boolean,
            default: false,
        },
    },

    emits: ["update:searchTerm"],

    data() {
        return {
            active: false,
            selectedOptionIndex: null,
        };
    },

    computed: {
        selectedOption() {
            if (this.selectedOptionIndex) {
                return this.selectOptions[this.selectedOptionIndex];
            }
            return {};
        },
        selectOptions() {
            const options = _.cloneDeep(this.options);
            options.unshift({
                primary: "Please select an option",
                secondary: "",
                id: -1,
            });
            return options;
        },
    },

    methods: {
        updateActive(val) {
            this.active = val;
        },
        updatedSelectedOption(index) {
            this.selectedOptionIndex = index;
        },
        search(term) {
            this.$emit("update:searchTerm", term);
        },
    },
};
</script>
