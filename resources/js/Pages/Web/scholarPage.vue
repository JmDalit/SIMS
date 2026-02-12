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
                    @saveForm="submitForm"
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
                    @buttonOpenModal="toggleModal()"
                    message-type="error"
                    ref="toolbarRef"
                >
                    <template #form>
                        <div class="my-1">
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
                        </div>
                        <Divider type="dashed" align="left">
                            <span class="text-xs font-bold"
                                >Uploaded Files</span
                            >
                        </Divider>
                        <div class="flex justify-end mb-3">
                            <SelectButton
                                v-model="filterFileStatus"
                                :options="filterFileOption"
                                @update:model-value="loadFilePage"
                                size="small"
                            />
                        </div>

                        <DefaultTable
                            :items="page.props.files.data"
                            :pagination="{
                                total: page.props.files.total,
                                perPage: page.props.files.per_page,
                                currentPage: page.props.files.current_page,
                            }"
                            :loading="filesLoadingTable"
                            @paginate="loadFilePage"
                        >
                            <Column header="Files">
                                <template #body="props">
                                    <div class="flex items-center gap-2">
                                        <Avatar
                                            style="
                                                background-color: #dee9fc;
                                                color: #1a2551;
                                            "
                                            class="!rounded-xl !w-10 !h-10"
                                        >
                                            <IconFileText size="25" />
                                        </Avatar>
                                        <div class="flex flex-col">
                                            <div>{{ props.data.filename }}</div>
                                            <a
                                                :href="props.data.file_url"
                                                download
                                                class="text-blue-500 underline text-xs"
                                                >Click to download</a
                                            >
                                        </div>
                                    </div>
                                </template>
                            </Column>
                            <Column header="Created By">
                                <template #body="props">
                                    <div class="flex flex-col text-xs">
                                        <div>{{ props.data.created_by }}</div>
                                        <div
                                            class="font-semibold text-gray-500"
                                        >
                                            {{ props.data.formatted_date }}
                                        </div>
                                    </div>
                                </template>
                            </Column>
                            <Column>
                                <template #header>
                                    <div
                                        class="flex justify-center w-full text-xs font-semibold"
                                    >
                                        <div>Validate Status</div>
                                    </div>
                                </template>
                                <template #body="props">
                                    <div
                                        class="flex justify-center items-center"
                                    >
                                        <div
                                            v-if="
                                                props.data.status == 'pending'
                                            "
                                            class="bg-amber-50 px-3 py-1 rounded-2xl antialiased text-amber-600 flex gap-2 items-center"
                                        >
                                            <IconDotsCircleHorizontal
                                                size="15"
                                                stroke-width="3"
                                            />
                                            <div class="text-xs uppercase">
                                                Pending
                                            </div>
                                        </div>
                                        <div
                                            v-else-if="
                                                props.data.status == 'accept'
                                            "
                                            class="bg-green-50 px-3 py-1 rounded-2xl antialiased text-green-600 flex gap-2 items-center"
                                        >
                                            <IconCircleCheck
                                                size="15"
                                                stroke-width="3"
                                            />
                                            <div class="text-xs uppercase">
                                                Accept
                                            </div>
                                        </div>
                                        <div
                                            v-else
                                            class="bg-red-50 px-3 py-1 rounded-2xl antialiased text-red-600 flex gap-2 items-center"
                                        >
                                            <IconCircleX
                                                size="15"
                                                stroke-width="3"
                                            />
                                            <div class="text-xs uppercase">
                                                reject
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </Column>

                            <Column
                                v-if="
                                    filterFileStatus == 'Pending' ||
                                    filterFileStatus == null
                                "
                            >
                                <template #header>
                                    <div
                                        class="flex justify-end w-full text-xs font-semibold"
                                    >
                                        <IconSettings :size="20" />
                                    </div>
                                </template>
                                <template #body="props">
                                    <div
                                        class="flex justify-end items-center gap-2"
                                    >
                                        <DefaultButton
                                            rounded
                                            size="small"
                                            severity="danger"
                                            text
                                            tooltip="Reject"
                                            :icon="IconX"
                                            @click="
                                                validateFiles({
                                                    type: 'reject',
                                                    id: props.data.hash_id,
                                                })
                                            "
                                        ></DefaultButton>
                                        <DefaultButton
                                            rounded
                                            size="small"
                                            severity="success"
                                            tooltip="Accept"
                                            text
                                            :icon="IconCheck"
                                            @click="
                                                validateFiles({
                                                    type: 'accept',
                                                    id: props.data.hash_id,
                                                })
                                            "
                                        ></DefaultButton>
                                    </div>
                                </template>
                            </Column>
                        </DefaultTable>
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
    IconDotsCircleHorizontal,
    IconSettings,
    IconCircleX,
    IconCircleCheck,
} from "@tabler/icons-vue";

const page = usePage();
const searchInput = ref(null);
const timerBounce = ref(null);
const selectedRow = ref(null);
const toolbarRef = ref(null);
const toastRef = ref(null);
const filesLoadingTable = ref(false);
const confirmRef = ref(null);
const menu = ref(null);
const filterFileStatus = ref("Pending");

const filterFileOption = ref(["Pending", "Accept", "Reject"]);
const suggestions = ref(null);
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
const openHistoryFiles = () => {};

const toggleModal = () => {
    if (filesUploadForm.files.length != 0) {
        filesUploadForm.files = [];
        filesUploadForm.resetAndClearErrors();
    }
    router.reload({
        data: { OpenFiles: true, status: "Pending", page: 1 },
        only: ["files"],
        onSuccess: () => {
            toolbarRef.value.openModal();
        },
    });
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

const submitForm = () => {
    filesUploadForm.post(route("scholar.store"), {
        forceFormData: true,
        onSuccess: () => {
            toastRef.value.show(page.props.flash);
            filesUploadForm.files = [];
            filesUploadForm.resetAndClearErrors();
        },
    });
};

const validateFiles = (res) => {
    router.post(
        route("scholar.insert", res.id),
        {
            status: res.type,
        },
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                toastRef.value.show(page.props.flash);
            },
        },
    );
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

const loadFilePage = (page) => {
    filesLoadingTable.value = true;
    router.reload({
        data: { OpenFiles: true, page, status: filterFileStatus.value },
        only: ["files"],
        onFinish: () => (filesLoadingTable.value = false),
    });
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
