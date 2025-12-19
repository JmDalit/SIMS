<template>
    <Head title="Courses" />
    <AuthLayout>
        <div class="flex flex-col w-full h-full gap-10">
            <div class="flex">
                <HeaderModule
                    title="List of Courses"
                    description="Course information and management"
                />
            </div>
            <div class="flex-1 flex flex-col gap-2">
                <ToolbarModule
                    v-model="searchInput"
                    @deleteSearch="clearSearch"
                    @saveForm="submitForm"
                    button-label="Create"
                    :dialog-title="
                        !scholarForm.id ? 'Create Scholar' : 'Edit Scholar'
                    "
                    dialog-description="Define a new scholar and configure its access permissions."
                    :dialog-button-loading="scholarForm.processing"
                    :dialog-icon="IconUserCog"
                    dialog-button-label="Save"
                    :message-has-errors="scholarForm.hasErrors"
                    :message-errors="scholarForm.errors"
                    @buttonOpenModal="toggleModal({ type: 'create' })"
                    message-type="error"
                    ref="toolbarRef"
                >
                    <template #form>
                        <Stepper v-model:value="scholarStepper">
                            <StepPanels>
                                <StepPanel :value="1">
                                    <div class="flex gap-3 mt-5">
                                        <div
                                            class="w-90 flex flex-col items-center justify-between"
                                        >
                                            <Avatar
                                                icon="pi pi-user"
                                                class="mr-2"
                                                size="xlarge"
                                            />
                                        </div>
                                        <Divider layout="vertical" />
                                        <div class="flex flex-col gap-5">
                                            <TextInput
                                                v-model="scholarForm.name"
                                                label="SPAS ID"
                                            ></TextInput>
                                            <TextInput
                                                v-model="scholarForm.name"
                                                label="Last Name"
                                            ></TextInput>
                                            <TextInput
                                                v-model="scholarForm.name"
                                                label="First Name"
                                            ></TextInput>
                                            <div
                                                class="flex items-center gap-4"
                                            >
                                                <TextInput
                                                    v-model="scholarForm.name"
                                                    label="Middle Name"
                                                ></TextInput>
                                                <TextInput
                                                    v-model="scholarForm.name"
                                                    label="Suffix"
                                                    class="!w-50"
                                                ></TextInput>
                                            </div>
                                        </div>
                                    </div>
                                </StepPanel>
                            </StepPanels>
                        </Stepper>
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
            v-model:visible="gradeDialog"
            hide-footer
            :icon="IconBook2"
            width-set="lg:!w-[80%]"
            title="Grades"
            description="View all subjects offered under this course, including their codes, units, and classifications."
        >
            <template #forms>
                <div
                    class="my-5"
                    v-for="(term, index) in page.props.scholarDetails"
                    :key="index"
                >
                    <Divider type="dashed"></Divider>
                    <div class="flex justify-between gap-5 p-2">
                        <div class="flex items-center gap-2">
                            <div class="bg-slate-200 p-2 shadow rounded-xl">
                                <IconFileDescription size="20" />
                            </div>
                            <div class="flex flex-col">
                                <div class="text-xs">TERM</div>
                                <div class="text-sm font-bold">
                                    {{ term.term }}
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="bg-slate-200 p-2 shadow rounded-xl">
                                <IconCalendarWeek size="20" />
                            </div>
                            <div class="flex flex-col">
                                <div class="text-xs">School Year</div>
                                <div class="text-sm font-bold">
                                    {{ term.school_year }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <DefaultScrollTable :items="term.subjects">
                        <Column
                            header="Description"
                            class="capitalize"
                            field="subject_name"
                            style="width: 30%"
                        >
                        </Column>
                        <Column header="Code" class="capitalize" field="code">
                        </Column>
                        <Column header="UNIT" class="capitalize" field="unit">
                        </Column>
                        <Column header="Grades" class="" field="grade">
                        </Column>
                        <Column header="status" class="status" field="status">
                        </Column>
                    </DefaultScrollTable>
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
import TextInput from "../../Components/inputs/TextInput.vue";
import DefaultToggle from "../../Components/toggleswitches/DefaultToggle.vue";
import DefaultToast from "../../Components/messages/DefaultToast.vue";
import DefaultConfirmDialog from "../../Components/dialogs/DefaultConfirmDialog.vue";
import DefaultScrollTable from "../../Components/tables/DefaultScrollTable.vue";
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
} from "@tabler/icons-vue";
import DefaultDialog from "../../Components/dialogs/DefaultDialog.vue";

const page = usePage();
const searchInput = ref(null);
const scholarStepper = ref(1);
const timerBounce = ref(null);
const selectedRow = ref(null);
const toolbarRef = ref(null);
const toastRef = ref(null);
const confirmRef = ref(null);
const menu = ref(null);
const suggestions = ref(null);
const gradeDialog = ref(false);
const scholarForm = useForm({
    id: null,
    name: null,
    field: null,
    abbreviation: null,
    isActive: false,
});

const toggleOption = (event, rowData) => {
    selectedRow.value = rowData;
    menu.value.toggle(event);
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

const openGradeScholar = () => {
    gradeDialog.value = true;
};

const toggleModal = (res) => {
    scholarForm.resetAndClearErrors();

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
            }
        );
    });
};

const submitForm = () => {
    if (!scholarForm.id) {
        scholarForm.post(route("academic.courses.store"), {
            onSuccess: () => {
                scholarForm.resetAndClearErrors();
                toastRef.value.show(page.props.flash);
            },
        });
    } else {
        scholarForm.put(
            route("academic.courses.update", {
                id: scholarForm.id,
                type: "form",
            }),
            {
                onSuccess: () => {
                    toolbarRef.value.closeModal();
                    scholarForm.resetAndClearErrors();
                    toastRef.value.show(page.props.flash);
                },
            }
        );
    }
};
const updateStatus = (result) => {
    scholarForm.isActive = result.is_active;
    scholarForm.put(
        route("academic.courses.update", { id: result.id, type: "status" }),
        {
            onSuccess: () => {
                toastRef.value.show(page.props.flash);
            },
        }
    );
};

const clearSearch = () => {
    searchInput.value = null;
};

const autoSearch = (event) => {
    setTimeout(() => {
        if (!event.trim().length) {
            suggestions.value = [...countries.value];
        } else {
            suggestions.value = page.props.categories.filter((search) => {
                return search.name.toLowerCase().includes(event.toLowerCase());
            });
        }
    }, 250);
};

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
        }
    );
};

watch(
    () => searchInput.value,
    () => {
        clearTimeout(timerBounce.value);
        timerBounce.value = setTimeout(() => {
            loadPage(1);
        }, 300);
    }
);
</script>
