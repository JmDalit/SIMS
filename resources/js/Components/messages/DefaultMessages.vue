<template>
    <Message
        :severity="messageType"
        :pt="{
            root: {
                class:
                    messageType === 'error'
                        ? 'dark:!bg-red-800/30 dark:!text-red-500 dark:!outline-red-700'
                        : messageType === 'warn'
                          ? 'dark:!bg-yellow-800/30 dark:!text-yellow-500 dark:!outline-yellow-700'
                          : messageType === 'info'
                            ? 'dark:!bg-blue-800/30 dark:!text-blue-500 dark:!outline-blue-700'
                            : messageType === 'success'
                              ? 'dark:!bg-green-800/30 dark:!text-green-500 dark:!outline-green-700'
                              : '',
            },
        }"
        :closable="closable"
    >
        <div class="flex items-center gap-2">
            <IconExclamationCircleFilled v-if="messageType === 'warn'" />
            <IconInfoCircleFilled v-else-if="messageType === 'info'" />
            <IconCircleCheckFilled v-else-if="messageType === 'success'" />
            <IconCircleXFilled v-else-if="messageType === 'error'" />
            <div class="text-xs">
                {{ firstError }}
            </div>
        </div>
    </Message>
</template>
<script setup>
import { computed } from "vue";

import {
    IconExclamationCircleFilled,
    IconInfoCircleFilled,
    IconCircleCheckFilled,
    IconCircleXFilled,
} from "@tabler/icons-vue";
const props = defineProps({
    messageType: String,
    message: [Array, Object],
    icon: [Object, Function],
    closable: {
        type: Boolean,
        default: false,
    },
});
const firstError = computed(() => {
    const errors = props.message ? Object.values(props.message) : [];
    return errors.length ? errors[0] : null;
});
</script>
