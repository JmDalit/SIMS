<template>
    <FileUpload
        mode="basic"
        name="files[]"
        :url="url"
        :accept="accept"
        :maxFileSize="maxFileSize"
        :auto="auto"
        :disabled="disabled"
        :chooseLabel="label"
        class="!text-sm !py-1 !px-5 !rounded-2xl"
        @upload="onUpload"
    />
</template>

<script setup>
defineProps({
    label: { type: String, default: "Upload File" },
    message: { type: String, default: null },
    disabled: { type: Boolean, default: false },
    url: { type: String, required: true },
    accept: { type: String, default: "*" },
    maxFileSize: { type: Number, default: 1000000 },
    auto: { type: Boolean, default: true },
});

const emit = defineEmits(["upload"]);

const onUpload = (event) => {
    emit("upload", event);
};
</script>
