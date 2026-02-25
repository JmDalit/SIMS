<template>
    <Head title="Scholars" />
    <AuthLayout>
        <div class="flex flex-col w-full h-full gap-5">
            <div class="flex flex-col lg:flex-row items-center space-x-0 gap-4">
                <HeaderModule
                    title="Scholar Management"
                    description="Comprehensive records of all scholars, including profile details, program assignments, and status monitoring."
                />
                <div class="flex">
                    <div
                        class="flex rounded-xl overflow-hidden border border-gray-200 shadow-sm"
                    >
                        <div
                            v-for="(item, index) in page.props?.statuses"
                            :key="index"
                            :class="[
                                'flex items-center gap-2 px-4 py-2 bg-white hover:bg-gray-50',
                                {
                                    'border-r border-gray-200':
                                        index !==
                                        page.props.statuses.length - 1,
                                },
                            ]"
                            v-tooltip.bottom="item.status"
                        >
                            <div
                                :class="[
                                    'p-1.5 rounded-lg',
                                    item.color_array.bgColor,
                                ]"
                            >
                                <component
                                    :is="TablerIcons[item.icon]"
                                    :class="[
                                        'w-4 h-4',
                                        item.color_array.textColor,
                                    ]"
                                />
                            </div>
                            <div class="flex flex-col">
                                <span
                                    class="text-xs text-gray-400 leading-none"
                                    >{{ item.status?.name }}</span
                                >
                                <span
                                    class="text-md font-semibold text-gray-700"
                                    >{{ item.total }}</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
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
                                ref="uploadRef"
                                @select-files="handleFiles"
                                @remove-file="clearForm"
                                :progress="progressUpload"
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

                            <Column v-if="filterFileStatus == 'Pending'">
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
                <DefaultSelectionTable
                    :items="page.props.scholar.data"
                    :pagination="{
                        total: page.props.scholar.total,
                        perPage: page.props.scholar.per_page,
                        currentPage: page.props.scholar.current_page,
                    }"
                    @selected="openModal"
                    :loading="scholarLoadingTable"
                    @paginate="loadPage"
                >
                    <Column header="Scholars">
                        <template #body="props">
                            <div class="flex items-center gap-2">
                                <div>
                                    <Avatar
                                        v-if="props.data.photo == null"
                                        :label="
                                            props.data.fullname
                                                .charAt(0)
                                                .toUpperCase()
                                        "
                                        class="!w-[40px] !h-[40px] !rounded-xl"
                                    />

                                    <Avatar
                                        v-else
                                        style="
                                            background-color: #dee9fc;
                                            color: #1a2551;
                                        "
                                        :image="page.props.user.avatar"
                                    />
                                </div>
                                <div class="flex flex-col">
                                    <div
                                        :class="[
                                            'text-xs flex items-center',
                                            props.data.sex == 'M'
                                                ? '!text-blue-600'
                                                : '!text-rose-600',
                                        ]"
                                    >
                                        <div>{{ props.data.spas_no }}</div>
                                    </div>
                                    <div class="font-medium">
                                        {{ props.data.fullname }}
                                    </div>
                                </div>
                            </div>
                        </template>
                    </Column>
                    <Column header="School/Course">
                        <template #body="props">
                            <div class="flex flex-col">
                                <div class="text-xs text-gray-400 font-light">
                                    {{ props.data.course }}
                                </div>
                                <div class="">
                                    {{ props.data.school }}
                                </div>
                            </div>
                        </template>
                    </Column>

                    <Column header="Type">
                        <template #body="props">
                            <div class="flex items-center">
                                <p
                                    :class="[
                                        props.data.type == 'JLSS'
                                            ? 'text-amber-400'
                                            : 'text-green-400',
                                    ]"
                                >
                                    ●
                                </p>
                                &nbsp;
                                {{ props.data.type }}
                            </div>
                        </template>
                    </Column>
                    <Column header="Program">
                        <template #body="props">
                            <div class="flex items-center">
                                <p
                                    :class="[
                                        props.data.program == 'MERIT'
                                            ? 'text-indigo-400'
                                            : props.data.program == 'RA 10612'
                                              ? 'text-lime-400'
                                              : 'text-pink-400',
                                    ]"
                                >
                                    ●
                                </p>
                                &nbsp;
                                {{ props.data.program }}
                            </div>
                        </template>
                    </Column>
                    <Column>
                        <template #header>
                            <div
                                class="flex justify-center w-full font-semibold"
                            >
                                <div class="font-semibold">Award Year</div>
                            </div>
                        </template>
                        <template #body="props">
                            <div class="text-center font-medium">
                                {{ props.data.awardyear }}
                            </div>
                        </template>
                    </Column>
                    <Column>
                        <template #header>
                            <div
                                class="flex justify-center w-full font-semibold"
                            >
                                <div class="font-semibold">Status</div>
                            </div>
                        </template>
                        <template #body="props">
                            <div class="flex justify-center">
                                <div
                                    :class="[
                                        ' flex items-center capitalize  rounded-2xl py-1 px-3 gap-1',
                                        props.data.status.bcolor,
                                        props.data.status.tcolor,
                                    ]"
                                >
                                    <component
                                        :is="
                                            TablerIcons[props.data.status.icon]
                                        "
                                        :size="20"
                                        :stroke="2"
                                    />
                                    <div class="">
                                        {{ props.data.status.name }}
                                    </div>
                                </div>
                            </div>
                        </template>
                    </Column>
                </DefaultSelectionTable>
            </div>
        </div>
        <DrawerScholarModule
            v-model:visible="scholarDrawer"
            :details="selectedRow"
        />
        <DefaultToast ref="toastRef" />
        <DefaultConfirmDialog ref="confirmRef" />
    </AuthLayout>
</template>
<script setup>
import AuthLayout from "../../Layouts/AuthLayout.vue";
import HeaderModule from "../../Modules/Others/HeaderModule.vue";
import DefaultTable from "../../Components/tables/DefaultTable.vue";
import DefaultSelectionTable from "../../Components/tables/DefaultSelectionTable.vue";
import ToolbarModule from "../../Modules/Others/ToolbarModule.vue";
import DefaultToast from "../../Components/messages/DefaultToast.vue";
import DefaultConfirmDialog from "../../Components/dialogs/DefaultConfirmDialog.vue";
import DefaultDialog from "../../Components/dialogs/DefaultDialog.vue";
import DrawerScholarModule from "../../Modules/Others/DrawerScholarModule.vue";
import UploadInput from "../../Components/inputs/UploadInput.vue";
import DefaultButton from "../../Components/buttons/DefaultButton.vue";
import * as TablerIcons from "@tabler/icons-vue";
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
    IconGenderMale,
    IconGenderFemale,
} from "@tabler/icons-vue";

const page = usePage();
const searchInput = ref(null);
const timerBounce = ref(null);
const selectedRow = ref(null);
const toolbarRef = ref(null);
const toastRef = ref(null);
const scholarLoadingTable = ref(false);
const filesLoadingTable = ref(false);
const scholarDrawer = ref(false);
const confirmRef = ref(null);
const uploadRef = ref(null);
const progressUpload = ref(0);
const filterFileStatus = ref("Pending");
const filterFileOption = ref(["Pending", "Accept", "Reject"]);
const filesUploadForm = useForm({
    files: [],
});

const handleFiles = (e) => {
    filesUploadForm.files = Array.from(e.files);
};

const clearForm = () => {
    filesUploadForm.files = [];
    filesUploadForm.resetAndClearErrors();
    progressUpload.value = 0;
};

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

const submitForm = () => {
    uploadRef.value.upload();
    filesUploadForm.post(route("scholar.store"), {
        forceFormData: true,
        onBefore: () => {
            progressUpload.value = 0;
        },
        onProgress: (e) => {
            progressUpload.value = Math.round(e.loaded / e.total) * 80;
        },
        onSuccess: () => {
            toastRef.value.show(page.props.flash);
            if (page.props.flash?.status == "success") {
                filesUploadForm.resetAndClearErrors();
                uploadRef.value.clear();
            }
            progressUpload.value = 100;
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

const openModal = (event) => {
    selectedRow.value = event;
    router.reload({
        data: { id: event.id },
        only: ["scholarDetails"],

        onSuccess: () => {
            scholarDrawer.value = true;
        },
    });
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
        route("scholars"),
        {
            page,
            ...(searchInput.value ? { search: searchInput.value } : {}),
        },
        {
            preserveState: true,
            preserveScroll: true,
            onBefore: () => (scholarLoadingTable.value = true),
            onFinish: () => (scholarLoadingTable.value = false),
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

watch(
    () => searchInput.value ?? null,
    () => {
        clearTimeout(timerBounce.value);
        timerBounce.value = setTimeout(() => {
            loadPage(1);
        }, 300);
    },
);
</script>
