<template>
    <Head title="Scholars" />
    <AuthLayout>
        <div class="flex flex-col w-full h-full gap-10">
            <div class="flex">
                <HeaderModule
                    title="Scholar Records"
                    description="Access all scholar profiles, programs, and status updates in one place."
                />
            </div>
            <div class="flex-1 flex flex-col gap-2">
                <ToolbarModule
                    v-model="searchInput"
                    @deleteSearch="clearSearch"
                    @saveForm="submitForm({ type: 'create' })"
                    button-label="Create"
                    :dialog-title="
                        !filesUploadForm.id ? 'Create Scholar' : 'Edit Scholar'
                    "
                    dialog-description="Define a new scholar and configure its access permissions."
                    :dialog-button-loading="filesUploadForm.processing"
                    :dialog-icon="IconUserCog"
                    dialog-button-label="Save"
                    :message-has-errors="filesUploadForm.hasErrors"
                    :message-errors="filesUploadForm.errors"
                    @buttonOpenModal="toggleModal({ type: 'create' })"
                    message-type="error"
                    ref="toolbarRef"
                >
                    <template #add1>
                        <DefaultButton
                            rounded
                            size="small"
                            :icon="IconFileTime"
                            :icon-size="25"
                            @click="openHistoryFiles"
                            text
                            tooltip="History Files"
                            severity="secondary"
                        ></DefaultButton>
                    </template>
                    <template #form>
                        <div class="my-5">
                            <div
                                class="flex justify-between text-xs text-gray-400"
                            >
                                <div class="flex items-end italic gap-1">
                                    <div>
                                        To get the scholars template, please
                                    </div>
                                    <a
                                        href="/templates/scholar_template.xlsx"
                                        download
                                        class="text-blue-500 underline text-xs"
                                        >Download Here</a
                                    >
                                </div>
                            </div>
                            <UploadInput
                                @remove-file="removeFile"
                                @select-files="handleFiles"
                                @single-remove-file="removeFile"
                                accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                            ></UploadInput>
                            <span class="text-gray-400 text-xs"
                                >**Please upload a CSV or Excel file containing
                                scholar</span
                            >
                        </div>
                    </template>
                </ToolbarModule>
                <DefaultTable
                    :items="page.props.scholar.data"
                    :pagination="{
                        total: page.props.scholar.total,
                        perPage: page.props.scholar.per_page,
                        currentPage: page.props.scholar.current_page,
                    }"
                    @paginate="loadPage"
                >
                    <Column header="Name">
                        <template #body="props">
                            <div class="flex flex-col">
                                <div class="text-xs">
                                    {{ props.data.spas_no }}
                                </div>
                                <div class="font-bold">
                                    {{ props.data.lname }},
                                    {{ props.data.fname }}
                                    {{ props.data.mname }}
                                </div>
                            </div>
                        </template>
                    </Column>
                    <Column header="School">
                        <template #body="props">
                            <div class="flex flex-col">
                                <div class="text-xs">
                                    {{ props.data.course }}
                                </div>
                                <div class="font-bold">
                                    {{ props.data.school }}
                                </div>
                            </div>
                        </template>
                    </Column>
                    <Column header="Program" field="program"> </Column>

                    <Column field="options" class="w-[5%]">
                        <template #body="prop">
                            <div class="flex w-full justify-end">
                                <Button
                                    text
                                    v-tooltip.top="'Options'"
                                    rounded
                                    size="small"
                                    severity="secondary"
                                    icon="pi pi-ellipsis-v"
                                    @click="(e) => toggleOption(e, prop.data)"
                                />
                                <Menu
                                    ref="menu"
                                    :model="menuItems"
                                    :popup="true"
                                >
                                    <template #item="{ item, props }">
                                        <a
                                            v-ripple
                                            class="flex items-center"
                                            v-bind="props.action"
                                            @click="item.command"
                                        >
                                            <div>
                                                <component
                                                    :is="item.icon"
                                                    :class="item.class"
                                                    size="20"
                                                    stroke-width="1.5"
                                                ></component>
                                            </div>
                                            <span class="ml-2 text-xs">{{
                                                item.label
                                            }}</span>
                                        </a>
                                    </template>
                                </Menu>
                            </div>
                        </template>
                    </Column>
                </DefaultTable>
            </div>
        </div>
        <DefaultToast ref="toastRef" />
        <DefaultConfirmDialog ref="confirmRef" />
        <DefaultDialog
            v-model:visible="historyFilesDialog"
            title="History Files"
            :icon="IconFileText"
            hide-footer
            description="Displays a record of all file uploads, modifications, and related activities."
        >
            <template #forms>
                <div class="pt-5">
                    <DefaultTable
                        :items="page.props.history.data"
                        :pagination="{
                            total: page.props.history.total,
                            perPage: page.props.history.per_page,
                            currentPage: page.props.history.current_page,
                        }"
                        @paginate="loadPage"
                    >
                        <Column header="Files"></Column>
                    </DefaultTable>
                </div>
            </template>
        </DefaultDialog>
    </AuthLayout>
</template>
<script setup>
import AuthLayout from "../../Layouts/AuthLayout.vue";
import HeaderModule from "../../Modules/Others/HeaderModule.vue";
import DefaultTable from "../../Components/tables/DefaultTable.vue";
import ToolbarModule from "../../Modules/Others/ToolbarModule.vue";
import DefaultToast from "../../Components/messages/DefaultToast.vue";
import DefaultConfirmDialog from "../../Components/dialogs/DefaultConfirmDialog.vue";
import DefaultDialog from "../../Components/dialogs/DefaultDialog.vue";
import UploadInput from "../../Components/inputs/UploadInput.vue";
import DefaultButton from "../../Components/buttons/DefaultButton.vue";

import { computed, ref, watch } from "vue";
import { Head, router, useForm, usePage } from "@inertiajs/vue3";
import {
    IconCheck,
    IconLock,
    IconUserCog,
    IconX,
    IconBook2,
    IconPencilCog,
    IconTrash,
    IconBooks,
    IconUserQuestion,
    IconCalendarWeek,
    IconFileDescription,
    IconFileTime,
    IconHistory,
    IconFileText,
} from "@tabler/icons-vue";

const page = usePage();
const searchInput = ref(null);
const timerBounce = ref(null);
const selectedRow = ref(null);
const toolbarRef = ref(null);
const toastRef = ref(null);
const confirmRef = ref(null);
const menu = ref(null);
const suggestions = ref(null);
const historyFilesDialog = ref(false);
const filesUploadForm = useForm({
    files: [],
});

const toggleOption = (event, rowData) => {
    selectedRow.value = rowData;
    menu.value.toggle(event);
};

const handleFiles = (e) => {
    filesUploadForm.files = Array.from(e.files);
};

const menuItems = computed(() => {
    if (!selectedRow.value) return [];

    return [
        {
            label: "View Subjects",
            icon: IconBooks,
            class: "text-gray-600",
            command: () => {
                openGradeScholar();
            },
        },
        {
            label: "Edit",
            icon: IconPencilCog,
            class: "text-blue-500",
            command: () => {
                toggleModal({
                    type: "edit",
                    data: selectedRow.value,
                });
            },
        },
        {
            label: "Delete",
            icon: IconTrash,
            class: "text-red-500",
            command: () => {
                deleteRow(selectedRow.value.id);
            },
        },
    ];
});
const openHistoryFiles = () => {
    historyFilesDialog.value = true;

    router.reload({
        data: { fileStatus: "open" },
        only: ["history"],
        preserveScroll: true,
    });
};

const toggleModal = (res) => {
    filesUploadForm.files = [];
    filesUploadForm.resetAndClearErrors();

    if (res.type === "edit") {
        scholarForm.id = res.data.id;
        scholarForm.name = res.data.name;
        scholarForm.abbreviation = res.data.abbreviation;

        scholarForm.field = res.data.suggestion_array;
    }

    toolbarRef.value.openModal();
};

const deleteRow = (id) => {
    confirmRef.value.popupDialog(() => {
        scholarForm.delete(
            route("academic.courses.destroy", { id: id, type: "delete" }),
            {
                onSuccess: () => {
                    scholarForm.resetAndClearErrors();
                    toastRef.value.show(page.props.flash);
                },
            },
        );
    });
};

const removeFile = (e) => {
    filesUploadForm.files = [];
    filesUploadForm.resetAndClearErrors();
};

const submitForm = (res) => {
    console.log(filesUploadForm.files);
    if (res.type === "create") {
        filesUploadForm.post(route("scholar.store"), {
            forceFormData: true,
            onSuccess: () => {
                toastRef.value.show(page.props.flash);
            },
        });
    }
};
// const updateStatus = (result) => {
//     scholarForm.isActive = result.is_active;
//     scholarForm.put(
//         route("academic.courses.update", { id: result.id, type: "status" }),
//         {
//             onSuccess: () => {
//                 toastRef.value.show(page.props.flash);
//             },
//         },
//     );
// };

const clearSearch = () => {
    searchInput.value = null;
};

// const autoSearch = (event) => {
//     setTimeout(() => {
//         if (!event.trim().length) {
//             suggestions.value = [...countries.value];
//         } else {
//             suggestions.value = page.props.categories.filter((search) => {
//                 return search.name.toLowerCase().includes(event.toLowerCase());
//             });
//         }
//     }, 250);
// };

const loadPage = (page) => {
    router.get(
        route("academic.courses"),
        {
            page,
            search: searchInput.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
};

// watch(
//     () => searchInput.value,
//     () => {
//         clearTimeout(timerBounce.value);
//         timerBounce.value = setTimeout(() => {
//             loadPage(1);
//         }, 300);
//     },
// );
</script>
