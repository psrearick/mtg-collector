<template>
    <div class="w-full">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4">
            <div class="md:col-span-2 lg:col-span-3">
                <label class="ml-3 text-xs text-gray-500" for="searchCard"
                    >Card Name</label
                >
                <div class="relative">
                    <div
                        class="
                            absolute
                            inset-y-0
                            left-0
                            pl-3
                            flex
                            items-center
                            pointer-events-none
                        "
                    >
                        <Icon icon="search" />
                    </div>
                    <input
                        id="searchCard"
                        name="searchCard"
                        class="
                            block
                            w-full
                            pl-10
                            pr-3
                            py-2
                            border
                            rounded-md
                            md:rounded-l-md md:rounded-r-none
                            leading-5
                            sm:text-sm
                            placeholder-gray-400
                            focus:outline-none
                            focus:ring-white
                            focus:text-gray-900
                            bg-white
                            border-gray-300
                            text-gray-700
                            focus:bg-gray-50
                            focus:border-gray-500
                        "
                        placeholder="Search Card Name"
                        type="search"
                        :value="modelValue"
                        @input="$emit('update:modelValue', $event.target.value)"
                    />
                </div>
            </div>
            <div>
                <label for="searchSet" class="ml-3 text-xs text-gray-500"
                    >Set Name</label
                >
                <div class="relative">
                    <div
                        class="
                            absolute
                            inset-y-0
                            left-0
                            pl-3
                            flex
                            items-center
                            pointer-events-none
                        "
                    >
                        <Icon icon="search" />
                    </div>
                    <input
                        id="searchSet"
                        name="searchSet"
                        class="
                            block
                            w-full
                            pl-10
                            pr-3
                            py-2
                            border
                            rounded-md
                            md:rounded-r-md md:rounded-l-none
                            leading-5
                            sm:text-sm
                            placeholder-gray-400
                            focus:outline-none
                            focus:ring-white
                            focus:text-gray-900
                            bg-white
                            border-gray-300
                            text-gray-700
                            focus:bg-gray-50
                            focus:border-gray-500
                        "
                        placeholder="Search Set Name/Code"
                        type="search"
                        :value="setName"
                        @input="$emit('update:setName', $event.target.value)"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Icon from "@/Components/Icon";

export default {
    name: "CardSetSearch",

    components: { Icon },

    props: {
        setName: {
            type: String,
            default: "",
        },
        modelValue: {
            type: String,
            default: "",
        },
    },
    emits: ["update:setName", "update:modelValue"],
};
</script>
