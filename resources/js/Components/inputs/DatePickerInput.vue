<template>
    <div class="w-full flex flex-col">
        <span class="text-sm font-medium" v-show="label">{{ label }}</span>

        <DatePicker
            v-model="modelValue"
            :view="view"
            :dateFormat="formatDate"
            fluid
            :pt="{
                pcInputText: {
                    root: [
                        'dark:!bg-gray-700 !text-sm  border dark:!border-gray-700 dark:!text-white',
                        capitalize ? 'capitalize' : '',
                    ],
                },
                panel: {
                    class: ' !text-sm',
                },
            }"
        />

        <Message
            severity="secondary"
            class="mt-1 mx-1"
            variant="simple"
            v-show="message"
            ><p class="text-xs font-light text-gray-400">{{ message }}</p>
        </Message>
    </div>
</template>
<script setup>
defineProps({
    type: {
        type: String,
        default: "text",
    },
    label: {
        type: String,
        default: null,
    },
    placeholder: {
        type: String,
    },
    capitalize: { type: Boolean, default: false },
    error: { type: [String, Array, Object], default: "" },
    message: {
        type: String,
        default: null,
    },
    view: {
        type: String,
        default: "date",
    },
    formatDate: {
        type: String,
        default: "mm/dd/yy",
    },
});
const modelValue = defineModel({
    type: [Date, null],
    required: true,
});
</script>
<style scoped>
::v-deep(.p-tooltip-text) {
    font-size: 10px !important;
}
</style>
