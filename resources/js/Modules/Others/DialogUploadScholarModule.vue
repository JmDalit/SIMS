<template>
    <Dialog
        v-model:visible="modelValue"
        modal
        :style="{ width: '40rem' }"
        :pt="{ header: 'border-b-1 border-gray-300 border-dashed' }"
    >
        <template #header>
            <div
                class="bg-slate-100 px-4 py-2 shadow rounded-lg flex items-center gap-2"
            >
                <IconUserUp :size="18" :stroke-width="2" />
                <div class="uppercase font-medium text-sm">Upload Scholars</div>
            </div>
        </template>
        <template #default>
            <div class="my-1">
                <div class="flex justify-between text-xs text-gray-400">
                    <div class="flex items-end italic gap-1">
                        <div>To get the scholars template, please</div>
                        <a
                            href="/templates/scholar_template.xlsx"
                            download
                            class="text-blue-500 underline text-xs"
                            >Download Here</a
                        >
                    </div>
                </div>
                <UploadInput
                    ref="uploadRef"
                    @select-files="handleFiles"
                    @remove-file="clearForm"
                    :progress="progressUpload"
                    accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                ></UploadInput>
            </div>
        </template>
    </Dialog>
</template>
<script setup>
import { IconUserUp } from "@tabler/icons-vue";
import UploadInput from "../../Components/inputs/UploadInput.vue";

const modelValue = defineModel("modelValue");
</script>
