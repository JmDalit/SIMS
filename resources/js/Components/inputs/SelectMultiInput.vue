<template>
    <div class="w-full flex flex-col">
        <span class="text-sm font-medium" v-show="label">{{ label }}</span>
        <MultiSelect
            :placeholder="placeholder"
            v-model="modelValue"
            :disabled="disable"
            :filter="filter"
            optionLabel="name"
            :optionGroupChildren="['id','name']"
            display="chip"
            @update:modelValue="emit('update-model', $event)"
            :options="options"
            :showClear="clearable"
            fluid
            :maxSelectedLabels="3"
            :pt="{
                root: {
                    class: [
                        'dark:!bg-gray-700  !text-sm dark:!border-gray-700 ',
                        capitalize ? 'capitalize' : '',
                    ],
                },
                option: {
                    class: [
                        '!text-sm dark:!text-gray-400 hover:!text-gray-700',
                        capitalize ? 'capitalize' : '',
                    ],
                },
                overlay: {
                    class: 'dark:!bg-gray-800  dark:!border-gray-700 ',
                },
                emptyMessage: {
                    class: '!text-sm',
                },
                pcFilter: {
                    root: 'dark:!bg-gray-700 !text-sm  dark:!text-white dark:!border-gray-700',
                },
            }"
        />
    </div>
</template>
<script setup>
import { watch } from "vue";
defineProps({
    label: {
        type: String,
        default: null,
    },
    disable: {
        type: Boolean,
        default: false,
    },
    capitalize: {
        type: Boolean,
        default: false,
    },
    placeholder: {
        type: String,
        default: "Select an option",
    },
    options: {
        type: [Object, Array],
        required: true,
    },
    filter: {
        type: [Boolean],
        default: false,
    },
    clearable: {
        type: [Boolean],
        default: false,
    },
});
const modelValue = defineModel({
    type: [Array, Object, null],
    required: true,
});
const emit = defineEmits(["update", "update-model"]);

watch(modelValue, (newVal) => {
    emit("update", newVal);
});
</script>
<style scoped>
::v-deep(.p-select-label) {
    font-size: 14px !important;
}
</style>
