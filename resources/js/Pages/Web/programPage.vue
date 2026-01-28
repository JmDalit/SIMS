<template>
    <Head title="Provinces" />
    <AuthLayout>
        <div class="flex flex-col w-full h-full gap-10">
            <div class="flex">
                <HeaderModule
                    title="List of Provinces"
                    description="Province information and management"
                />
            </div>
            <div class="flex-1 flex flex-col gap-2">
                <ToolbarModule
                    v-model="searchInput"
                    @deleteSearch="clearSearch"
                    @saveForm="submitForm"
                    button-label="Create"
                    :dialog-title="
                        !programForm.id ? 'Create Role' : 'Edit Role'
                    "
                    dialog-description="Define a new role and configure its access permissions."
                    :dialog-button-loading="programForm.processing"
                    :dialog-icon="IconUserCog"
                    dialog-button-label="Save"
                    :message-has-errors="programForm.hasErrors"
                    :message-errors="programForm.errors"
                    @buttonOpenModal="toggleModal({ type: 'create' })"
                    message-type="error"
                    ref="toolbarRef"
                >
                    <template #form>
                        <div class="flex flex-col gap-3 mt-5">
                            <TextInput
                                v-model="programForm.name"
                                label="Name"
                            ></TextInput>
                            <TextInput
                                v-model="programForm.description"
                                label="Description"
                            ></TextInput>
                            <SelectInput
                                label="Scholarship"
                                v-model="programForm.scholarship"
                                :options="page.props.scholarshipOptions"
                                :clearable="true"
                                capitalize
                            ></SelectInput>
                            <SelectInput
                                label="Type"
                                v-model="programForm.type"
                                :options="page.props.typeOptions"
                                :clearable="true"
                                capitalize
                            ></SelectInput>
                            <div>
                                <Divider type="dashed" />
                                <div class="flex justify-between items-center">
                                    <div class="text-sm">
                                        Make this Sub Program?
                                    </div>
                                    <DefaultToggle
                                        v-model="programForm.isActive"
                                        :check-icon="IconCheck"
                                        :un-check-icon="IconX"
                                    />
                                </div>
                            </div>
                        </div>
                    </template>
                </ToolbarModule>
                <DefaultTable
                    :items="page.props.programs.data"
                    :pagination="{
                        total: page.props.programs.total,
                        perPage: page.props.programs.per_page,
                        currentPage: page.props.programs.current_page,
                    }"
                    @paginate="loadPage"
                >
                    <Column
                        field="name"
                        header="Name"
                        class="font-semibold"
                    ></Column>
                    <Column field="others" header="Description"></Column>
                    <Column
                        field="program_array.name"
                        header="Scholarship"
                    ></Column>
                    <Column field="type_array.name" header="Type"></Column>
                    <Column field="is_sub" class="!text-center">
                        <template #header>
                            <div
                                class="flex justify-center w-full font-semibold"
                            >
                                <div>Is Sub</div>
                            </div>
                        </template>
                        <template #body="slotProps">
                            <span
                                :class="[
                                    slotProps.data.is_sub
                                        ? 'bg-blue-100 text-blue-600 '
                                        : 'bg-orange-100 text-orange-600',
                                    'text-xs font-semibold px-4 py-1 rounded-full',
                                ]"
                            >
                                {{
                                    slotProps.data.is_sub
                                        ? "Subprogram"
                                        : "Main"
                                }}
                            </span>
                        </template>
                    </Column>
                    <Column field="status" class="w-[5%]">
                        <template #header>
                            <div class="w-full flex justify-center">
                                <p class="font-semibold">Status</p>
                            </div>
                        </template>
                        <template #body="props">
                            <div
                                class="flex items-center justify-center w-full"
                            >
                                <DefaultToggle
                                    :check-icon="IconCheck"
                                    :un-check-icon="IconX"
                                    v-model="props.data.is_active"
                                    @update-value="updateStatus(props.data)"
                                />
                            </div>
                        </template>
                    </Column>
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
import { Head, router, useForm, usePage } from "@inertiajs/vue3";
import AuthLayout from "../../Layouts/AuthLayout.vue";
import HeaderModule from "../../Modules/Others/HeaderModule.vue";
import DefaultTable from "../../Components/tables/DefaultTable.vue";
import ToolbarModule from "../../Modules/Others/ToolbarModule.vue";
import TextInput from "../../Components/inputs/TextInput.vue";
import DefaultToggle from "../../Components/toggleswitches/DefaultToggle.vue";
import { computed, ref, watch } from "vue";
import {
    IconCheck,
    IconLock,
    IconUserCog,
    IconX,
    IconPencilCog,
    IconTrash,
} from "@tabler/icons-vue";
import DefaultToast from "../../Components/messages/DefaultToast.vue";
import DefaultConfirmDialog from "../../Components/dialogs/DefaultConfirmDialog.vue";
import SelectInput from "../../Components/inputs/SelectInput.vue";

const page = usePage();
const searchInput = ref(null);
const timerBounce = ref(null);
const selectedRow = ref(null);
const toolbarRef = ref(null);
const toastRef = ref(null);
const confirmRef = ref(null);
const menu = ref(null);
const programForm = useForm({
    id: null,
    name: null,
    description: null,
    scholarship: null,
    type: null,
    isSub: false,
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

const toggleModal = (res) => {
    programForm.resetAndClearErrors();

    if (res.type == "edit") {
        programForm.id = res.data.id;
        programForm.name = res.data.name;
        programForm.oldName = res.data.old_name;
        programForm.code = res.data.code;
        programForm.region = res.data.region_array;
    }

    toolbarRef.value.openModal();
};

const deleteRow = (id) => {
    confirmRef.value.popupDialog(() => {
        programForm.delete(
            route("programs.destroy", { id: id, type: "delete" }),
            {
                onSuccess: () => {
                    programForm.resetAndClearErrors();
                    toastRef.value.show(page.props.flash);
                },
            },
        );
    });
};

const submitForm = () => {
    if (!programForm.id) {
        programForm.post(route("programs.store"), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                programForm.resetAndClearErrors();
                toastRef.value.show(page.props.flash);
            },
        });
    } else {
        programForm.put(
            route("programs.update", {
                id: programForm.id,
                type: "form",
            }),
            {
                onSuccess: () => {
                    toolbarRef.value.closeModal();
                    programForm.resetAndClearErrors();
                    toastRef.value.show(page.props.flash);
                },
            },
        );
    }
};
const updateStatus = (result) => {
    programForm.isActive = result.is_active;
    programForm.put(
        route("programs.update", { id: result.id, type: "status" }),
        {
            onSuccess: () => {
                programForm.clearErrors();
                toastRef.value.show(page.props.flash);
            },
        },
    );
};

const clearSearch = () => {
    searchInput.value = null;
};

const loadPage = (page) => {
    router.get(
        route("programs"),
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

watch(
    () => searchInput.value,
    () => {
        clearTimeout(timerBounce.value);
        timerBounce.value = setTimeout(() => {
            loadPage(1);
        }, 300);
    },
);
</script>
