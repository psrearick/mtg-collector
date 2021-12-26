<template>
    <div :class="logoClasses">
        <Icon
            icon="collection"
            :classes="'mx-auto hover:text-blue-500 ' + classes"
        />
        <p v-if="showText" :class="textClass">MTG Collector</p>
    </div>
</template>

<script>
import Icon from "@/Components/Icon";

export default {
    name: "Logo",
    components: { Icon },
    props: {
        size: {
            type: Number,
            default: 48,
        },
        classes: {
            type: String,
            default: "",
        },
        textClass: {
            type: String,
            default: "text-xl",
        },
        showText: {
            type: Boolean,
            default: true,
        },
        textLocation: {
            type: String,
            default: "bottom",
        },
    },
    computed: {
        logoClasses() {
            if (this.textLocation === "bottom") {
                return "mx-auto text-center";
            }

            return "flex gap-4";
        },
    },
};
</script>
