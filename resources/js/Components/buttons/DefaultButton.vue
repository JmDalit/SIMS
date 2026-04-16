<template>
    <Button
        :type="type"
        :label="label"
        :disabled="disabled"
        :size="size"
        :loading="loading"
        :severity="severity"
        @click="onClick($event)"
        :variant="variant"
        :raised="raised"
        :badge="badge"
        badge-severity="danger"
        :text="text"
        :outlined="outlined"
        v-tooltip.top="tooltip"
        :class="[
            severity === 'primary' && !outlined && !text
                ? '!bg-blue-600 !border-blue-600 hover:!bg-blue-500 hover:!border-blue-500 active:!bg-blue-700 active:!border-blue-700'
                : '',
            className,
        ]"
        :rounded="rounded"
    >
        <template #icon>
            <div v-if="icon">
                <component :is="icon" :size="iconSize"></component>
            </div>
        </template>
    </Button>
</template>
<script setup>
const emit = defineEmits(["click"]);

defineProps({
    label: {
        type: [String],
        default: "",
    },
    type: {
        type: [String],
        default: "submit",
    },
    disabled: {
        type: [Boolean],
        default: false,
    },
    size: {
        type: [String],
        default: "",
    },
    iconSize: {
        type: [Number],
        default: 20,
    },
    loading: {
        type: [Boolean],
        default: false,
    },
    icon: {
        type: [Object, String, Function],
        default: null,
    },
    outlined: {
        type: [Boolean],
        default: false,
    },
    badge:{
        type: [String],
        default: null
    },
    severity: {
        type: [String],
        default: "primary",
    },
    raised: {
        type: [Boolean],
        default: false,
    },
    text: {
        type: [Boolean],
        default: false,
    },
    rounded: {
        type: [Boolean],
        default: false,
    },
    className: {
        type: String,
        default: null,
    },
    tooltip: {
        type: String,
        default: null,
    },
    variant: {
        type: String,
        default: null,
    },
});

function onClick(event) {
    emit("click", event);
}
</script>
