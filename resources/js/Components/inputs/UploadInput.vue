<template>
    <FileUpload
        name="demo[]"
        :show-upload-button="false"
        :pt="{
            root: { class: '!rounded-[15px] !m-0 !border-dashed' },
        }"
        :accept="accept"
        customUpload
        showCancelButton
        @remove="onRemoveUploadedFile"
        @select="(files) => emit('select-files', files)"
    >
        <template #empty>
            <div
                class="w-full flex items-center justify-center text-gray-400 text-sm italic"
            >
                <div class="flex flex-col items-center justify-center">
                    <IconFileUpload :size="40" stroke-width="1" />
                    <div>Drag and drop files to here to upload.</div>
                </div>
            </div>
        </template>
        <template
            #content="{
                files,
                uploadedFiles,
                removeUploadedFileCallback,
                removeFileCallback,
                progress,
                messages,
            }"
        >
            <div class="'flex flex-col w-full">
                <div class="" v-show="messages.length !== 0">
                    <DefaultMessages
                        message-type="error"
                        :message="messages"
                    ></DefaultMessages>
                </div>
                <div>
                    <div v-for="(item, key) in files" :key="key" class="w-full">
                        <div class="flex items-center gap-3 m-2">
                            <Avatar class="!w-[40px] !h-[40px]">
                                <IconFileDescription size="24" />
                            </Avatar>

                            <div class="flex flex-col gap-2 w-full">
                                <div class="flex justify-between">
                                    <div
                                        class="text-sm text-gray-700 flex items-end gap-2"
                                    >
                                        <div>{{ item.name }}</div>
                                        <div class="text-gray-400 text-[10px]">
                                            ({{ formatFileSize(item.size) }})
                                        </div>
                                    </div>
                                    <Button
                                        text
                                        severity="danger"
                                        class="!rounded-[15px] !h-[20px] !w-[20px] !p-0"
                                        @click="
                                            handleRemoveFile(
                                                key,
                                                item,
                                                removeFileCallback,
                                            )
                                        "
                                    >
                                        <IconX size="30" />
                                    </Button>
                                </div>

                                <ProgressBar
                                    class="!h-[10px] !rounded-[5px]"
                                    :pt="{ label: { class: '!text-[8px]' } }"
                                ></ProgressBar>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </FileUpload>
</template>
<script setup>
import { IconFileDescription, IconFileUpload, IconX } from "@tabler/icons-vue";
import { ref } from "vue";
import DefaultMessages from "../messages/DefaultMessages.vue";

const emit = defineEmits(["select-files", "remove-file", "single-remove-file"]);

defineProps({
    accept: {
        type: String,
        default: "",
    },
});

function formatFileSize(size) {
    const units = ["Bytes", "KB", "MB", "GB", "TB"];
    if (!size || size === 0) return "0 Bytes";
    const i = Math.floor(Math.log(size) / Math.log(1024));
    return (size / Math.pow(1024, i)).toFixed(2) + " " + units[i];
}
function onRemoveUploadedFile() {
    emit("remove-file");
}

const handleRemoveFile = (key, file, removeFileCallback) => {
    removeFileCallback(key);
    emit("remove-file", file);
};
</script>

<style>
.p-fileupload-choose-button {
    border-radius: 15px !important;
    font-size: 12px !important;
}
.p-fileupload-cancel-button {
    border-radius: 15px !important;
    font-size: 12px !important;
    color: #8d8d8d;
}
</style>
