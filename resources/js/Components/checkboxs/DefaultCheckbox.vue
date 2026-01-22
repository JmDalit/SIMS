<template>
    <div class="flex items-center gap-2">
        <Checkbox v-model="modelValue" :inputId="label + '_id'" :value="value" :binary="binary" :size="size" :pt="{
            box: {
                class: 'dark:!bg-gray-700 dark:!border-gray-600 !border !text-sm',
            },
        }" />
        <div>
            <label :for="label + '_id'" class="text-sm text-gray-500 whitespace-nowrap">
                {{ label }}
            </label>
        </div>

    </div>
</template>

<script setup>
defineProps({
    label: {
        type: String,
        default: "Label",
    },
    value: {
        type: [String, Boolean],
        default: true,
    },
    size: String,
    binary: {
        type: Boolean,
        default: false,
    },
});

const modelValue = defineModel({
    type: [String, Array, Boolean],
    required: true,
});
</script>
