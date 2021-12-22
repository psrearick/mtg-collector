<template>
    <div class="flex items-center h-5">
        <input
            :id="name"
            :checked="checked"
            :aria-describedby="describedBy"
            :name="name"
            type="checkbox"
            class="
                focus:ring-primary-500
                h-4
                w-4
                text-primary-600
                border-gray-300
                rounded
            "
            @change="check"
        />
    </div>
</template>

<script>
export default {
    name: "UiCheckbox",

    props: {
        value: {
            type: Boolean,
            default: false,
        },
        name: {
            type: String,
            default: "",
        },
        description: {
            type: String,
            default: "",
        },
    },

    emits: ["update:value"],

    data() {
        return {
            checked: false,
        };
    },

    computed: {
        describedBy() {
            return this.description || this.name;
        },
    },

    watch: {
        value(value) {
            this.checked = value;
        },
    },

    mounted() {
        this.checked = this.value;
    },

    methods: {
        check(value) {
            this.$emit("update:value", value.target.checked);
        },
    },
};
</script>
