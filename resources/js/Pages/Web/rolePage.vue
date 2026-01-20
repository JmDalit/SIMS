<template>
    <Head title="Roles" />
    <AuthLayout>
        <div class="flex flex-col w-full h-full gap-10">
            <div class="flex">
                <HeaderModule
                    title="Role Management"
                    description="Manage user roles, permissions, and access levels."
                />
            </div>
            <div class="flex-1 flex flex-col gap-2">
                <ToolbarModule
                    v-model="searchInput"
                    @deleteSearch="clearSearch"
                    @saveForm="submitForm"
                    button-label="Create"
                    :dialog-title="!roleForm.id ? 'Create Role' : 'Edit Role'"
                    dialog-description="Define a new role and configure its access permissions."
                    :dialog-button-loading="roleForm.processing"
                    :dialog-icon="IconUserCog"
                    dialog-button-label="Save"
                    :message-has-errors="roleForm.hasErrors"
                    :message-errors="roleForm.errors"
                    @buttonOpenModal="toggleModal({ type: 'create' })"
                    message-type="error"
                    ref="toolbarRef"
                >
                    <template #form>
                        <div class="flex flex-col gap-3 mt-5">
                            <TextInput
                                v-model="roleForm.name"
                                label="Name"
                                capitalize
                            ></TextInput>
                            <TextInput
                                v-model="roleForm.slug"
                                label="Slug"
                            ></TextInput>
                            <TextInput
                                v-model="roleForm.description"
                                label="Description"
                            ></TextInput>
                            <div>
                                <Divider type="dashed" />
                                <div class="flex justify-between items-center">
                                    <div class="text-sm">
                                        Make this role undeletable?
                                    </div>
                                    <DefaultToggle
                                        v-model="roleForm.isLock"
                                        :check-icon="IconCheck"
                                        :un-check-icon="IconX"
                                    />
                                </div>
                            </div>
                        </div>
                    </template>
                </ToolbarModule>
                <DefaultTable
                    :items="page.props.roles.data"
                    :pagination="{
                        total: page.props.roles.total,
                        perPage: page.props.roles.per_page,
                        currentPage: page.props.roles.current_page,
                    }"
                    @paginate="loadPage"
                >
                    <Column field="name" header="Name">
                        <template #body="props">
                            <div class="flex flex-col">
                                <div class="font-semibold capitalize">
                                    {{ props.data.name }}
                                </div>
                                <div class="text-gray-500 text-xs">
                                    {{ props.data.description }}
                                </div>
                            </div>
                        </template>
                    </Column>

                    <Column header="Users">
                        <template #body="props">
                            <div v-if="props.data.users?.length != 0">
                                <AvatarGroup>
                                    <Avatar
                                        v-for="user in props.data.users.slice(
                                            0,
                                            5
                                        )"
                                        :key="user.id"
                                        :label="
                                            user.email.charAt(0).toUpperCase()
                                        "
                                        v-tooltip.top="user.email"
                                        shape="circle"
                                        size="small"
                                        :style="{
                                            backgroundColor: '#ece9fc',
                                            color: '#2a1261',
                                        }"
                                    />

                                    <Avatar
                                        v-if="props.data.users.length > 5"
                                        :label="`+${
                                            props.data.users.length - 5
                                        }`"
                                        shape="circle"
                                        size="small"
                                        :style="{
                                            backgroundColor: '#d3d3d3',
                                            color: '#333',
                                            fontWeight: 'bold',
                                        }"
                                    />
                                </AvatarGroup>
                            </div>
                            <div v-else>
                                <span
                                    class="text-gray-400 font-light text-[12px] italic"
                                    >No users available</span
                                >
                            </div>
                        </template>
                    </Column>
                    <Column field="formatted_date" header="Created Date">
                    </Column>
                    <Column field="created_by" header="Created By" />
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
                                    :disabled="props.data.is_lock"
                                    v-if="!props.data.is_lock"
                                    @update-value="updateStatus(props.data)"
                                />
                                <div
                                    v-else
                                    v-tooltip.top="'Cant deactive this role.'"
                                >
                                    <IconLock size="18" />
                                </div>
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

const page = usePage();
const searchInput = ref(null);
const timerBounce = ref(null);
const selectedRow = ref(null);
const toolbarRef = ref(null);
const toastRef = ref(null);
const confirmRef = ref(null);
const menu = ref(null);
const roleForm = useForm({
    id: null,
    name: null,
    slug: null,
    description: null,
    isLock: false,
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
            disabled: selectedRow.value.is_lock ? true : false,
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
            disabled: selectedRow.value.is_lock ? true : false,
            command: () => {
                deleteRow(selectedRow.value.id);
            },
        },
    ];
});

const toggleModal = (res) => {
    roleForm.resetAndClearErrors();

    if (res.type == "edit") {
        roleForm.id = res.data.id;
        roleForm.name = res.data.name;
        roleForm.slug = res.data.slug;
        roleForm.description = res.data.description;
        roleForm.isLock = res.data.is_lock;
    }

    toolbarRef.value.openModal();
};

const deleteRow = (id) => {
    confirmRef.value.popupDialog(() => {
        roleForm.delete(route("roles.destroy", id), {
            onSuccess: () => {
                roleForm.resetAndClearErrors();
                toastRef.value.show(page.props.flash);
            },
        });
    });
};

const submitForm = () => {
    if (!roleForm.id) {
        roleForm.post(route("roles.store"), {
            onSuccess: () => {
                roleForm.resetAndClearErrors();
                toastRef.value.show(page.props.flash);
            },
        });
    } else {
        roleForm.put(route("roles.update", { id: roleForm.id, type: "form" }), {
            onSuccess: () => {
                toolbarRef.value.closeModal();
                roleForm.resetAndClearErrors();
                toastRef.value.show(page.props.flash);
            },
        });
    }
};
const updateStatus = (result) => {
    roleForm.isActive = result.is_active;
    roleForm.put(route("roles.update", { id: result.id, type: "status" }), {
        onSuccess: () => {
            toastRef.value.show(page.props.flash);
        },
    });
};

const clearSearch = () => {
    searchInput.value = null;
};

const loadPage = (page) => {
    router.get(
        route("roles"),
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
