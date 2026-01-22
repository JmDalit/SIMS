<template>
    <div class="w-full flex flex-col">
        <div class="flex items-center gap-1">
            <span class="text-sm font-medium">{{ label }}</span>
        </div>
        <Password
            :type="type"
            :placeholder="placeholder"
            v-model="modelValue"
            :toggleMask="toggleIcon"
            :strongRegex="strongRegex"
            :mediumRegex="mediumRegex"
            :feedback="feedback"
            :inputProps="{ autocomplete: 'new-password' }"
            fluid
            :pt="{
                pcInputText: {
                    root: 'dark:!bg-gray-700 !text-sm  dark:!text-white dark:!border-gray-700',
                },
                meterText: {
                    class: 'text-xs',
                },
                meter: {
                    class: '!h-[10px]',
                },
                overlay: {
                    class: 'dark:!bg-gray-800',
                },
                unmaskIcon: {
                    class: '!cursor-pointer ',
                },
                maskIcon: {
                    class: '!cursor-pointer ',
                },
            }"
        >
            <template #footer>
                <Divider />
                <div class="flex flex-col gap-4">
                    <div class="text-sm">
                        A valid password should meet the following requirements:
                    </div>
                    <ul
                        class="pl-2 my-0 leading-normal !text-xs flex flex-col gap-1"
                    >
                        <li>● Must be at least 6 characters long</li>
                        <li>● At least one lowercase</li>
                        <li>● At least one uppercase</li>
                        <li>● At least one numeric</li>
                        <li>● At least one special character</li>
                    </ul>
                </div>
            </template>
        </Password>
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
        default: "Label",
    },
    placeholder: {
        type: String,
    },
    toggleIcon: { type: Boolean, default: false },
    feedback: { type: Boolean, default: true },
    error: { type: [String, Array, Object], default: "" },
});
const modelValue = defineModel({
    type: [String, Date, Object, null, Number],
    required: true,
});

const mediumRegex = /^(?=.*[a-z])(?=.*[A-Z]).{6,}$/;
const strongRegex =
    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{8,}$/;
</script>
