<template>
    <Dialog
        v-model:visible="modelValue"
        modal
        class="dark:!bg-gray-800 dark:!text-gray-300 dark:!border-gray-600 !rounded-2xl"
        :style="{ width: '90rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
        :pt="{
            content: {
                class: '!p-3',
            },
            header: {
                class: 'border-b border-gray-400 border-dashed !p-3',
            },
            pcCloseButton: '!p-4',
        }"
    >
        <template #header>
            <div class="flex items-center justify-center gap-2">
                <Avatar class="!w-10 !h-10 !rounded-xl">
                    <component :is="icon" size="25"></component>
                </Avatar>

                <div class="antialiased">
                    <div class="text-normal font-normal">
                        {{ title }}
                    </div>
                    <div
                        v-show="subtitle"
                        class="text-xs text-gray-400 font-light"
                    >
                        {{ subtitle }}
                    </div>
                </div>
            </div>
        </template>
        <template #default>
            <div class="w-full h-full flex flex-col gap-4">
                <div class="flex-1 w-full">
                    <slot name="upper" />
                </div>
                <div class="flex-2">
                    <slot name="lower" />
                </div>
            </div>
        </template>
    </Dialog>
</template>

<script setup>
defineProps({
    description: String,
    title: String,
    subtitle: String,
    icon: Function,
    loading: Boolean,
    buttonLabel: {
        type: String,
        default: "Submit",
    },
    buttonIcon: {
        type: [Object, String, Function],
        default: null,
    },
    maximizable: {
        type: Boolean,
        default: false,
    },
    hideFooter: {
        type: Boolean,
        default: false,
    },
    widthSet: String,
    absoluteDiv: {
        type: Boolean,
        default: false,
    },
});

const modelValue = defineModel("visible");
</script>
