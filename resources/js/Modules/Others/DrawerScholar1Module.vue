<template>
    <Drawer
        v-model:visible="modelValue"
        position="full"
        :pt="{
            header: 'border-b-1 border-gray-300 border-dashed',
            content: 'bg-slate-50',
        }"
    >
        <template #header>
            <div
                class="bg-slate-100 px-4 py-2 shadow rounded-lg flex items-center gap-2"
            >
                <IconId :size="20" :stroke-width="2" />
                <div class="uppercase font-medium">Scholar Details</div>
            </div>
        </template>
        <template #default>
            <div class="flex flex-col lg:flex-row w-full pt-5 gap-3 h-full">
                <div class="lg:flex-2 flex flex-col bg-white p-2 rounded-2xl">
                    <div class="flex-1">
                        <div class="flex gap-2 items-center">
                            <div class="">
                                <Avatar
                                    v-if="
                                        page.props?.details?.profile?.photo ==
                                        null
                                    "
                                    class="!w-[9rem] !h-[9rem] !rounded-xl !text-5xl !bg-slate-300"
                                >
                                    <IconUserFilled :size="80" />
                                </Avatar>

                                <Avatar
                                    v-else
                                    :image="page.props.details?.profile?.photo"
                                    class="!w-[9rem] !h-[9rem] !bg-white shadow p-1 !rounded-xl"
                                />
                            </div>
                            <div class="flex-1 flex flex-col">
                                <div class="flex flex-col gap-2">
                                    <div class="">
                                        <div
                                            class="text-xs font-light text-gray-400 leading-none"
                                        >
                                            SPAS NO.
                                        </div>
                                        <div
                                            class="flex items-center text-sm gap-1"
                                        >
                                            <div
                                                v-tooltip.top="'Copy'"
                                                class="w-fit"
                                            >
                                                <IconCopy
                                                    :size="15"
                                                    class="cursor-pointer"
                                                />
                                            </div>
                                            <div class="">
                                                {{
                                                    page.props.details?.spas_no
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div
                                            class="text-xs font-light text-gray-400 leading-none"
                                        >
                                            NAME
                                        </div>

                                        <div
                                            class="flex items-center text-sm gap-1"
                                        >
                                            <div
                                                v-tooltip.top="'Copy'"
                                                class="w-fit"
                                            >
                                                <IconUser
                                                    :size="15"
                                                    class="cursor-pointer"
                                                />
                                            </div>
                                            <div
                                                class="font-medium text-gray-600 text-sm"
                                            >
                                                {{
                                                    page.props.details?.fullname
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div
                                            class="text-xs font-light text-gray-400 leading-none"
                                        >
                                            EMAIL
                                        </div>
                                        <div
                                            class="flex items-center text-sm gap-1"
                                        >
                                            <div
                                                v-tooltip.top="'Copy'"
                                                class="w-fit"
                                            >
                                                <IconAt
                                                    :size="15"
                                                    class="cursor-pointer"
                                                />
                                            </div>
                                            <div
                                                class="font-medium text-gray-600 text-sm"
                                            >
                                                {{ page.props.details?.email }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <Divider align="left">
                            <span class="text-xs font-medium"
                                >Scholar Details</span
                            >
                        </Divider>
                    </div>

                    <div class="flex-3 flex flex-col">
                        <div class="flex items-center justify-center">
                            <div
                                class="bg-slate-50 flex gap-7 rounded-2xl py-2 px-10"
                            >
                                <div
                                    class="flex flex-col items-center justify-center"
                                >
                                    <div>{{ page.props?.details.type }}</div>
                                    <div class="text-xs text-gray-400">
                                        Program
                                    </div>
                                </div>
                                <div
                                    class="flex flex-col items-center justify-center"
                                >
                                    <div>{{ page.props?.details.program }}</div>
                                    <div class="text-xs text-gray-400">
                                        Sub-Program
                                    </div>
                                </div>
                                <div
                                    class="flex flex-col items-center justify-center"
                                >
                                    <div>
                                        {{ page.props?.details.awardYear }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        Award Year
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 flex flex-col gap-3">
                            <div class="flex items-center gap-2">
                                <Avatar
                                    class="rounded-full border !bg-blue-50 !text-blue-500"
                                    size="small"
                                >
                                    <IconMapPin :size="20" stroke-width="2" />
                                </Avatar>
                                <div class="text-sm">
                                    {{ page.props?.details.region["name"] }}
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <Avatar
                                    class="rounded-full border !bg-blue-50 !text-blue-500"
                                    size="small"
                                >
                                    <IconSchool :size="20" stroke-width="2" />
                                </Avatar>
                                <div class="text-sm">
                                    {{ page.props?.details.course }}
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <Avatar
                                    class="rounded-full border !bg-blue-50 !text-blue-500"
                                    size="small"
                                >
                                    <IconBuildingEstate
                                        :size="20"
                                        stroke-width="2"
                                    />
                                </Avatar>
                                <div class="text-sm">
                                    {{ page.props?.details.school }}
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div
                                    :class="[
                                        'rounded-full border p-[5px]',
                                        page.props?.details?.status?.bcolor,
                                        page.props?.details?.status?.tcolor,
                                    ]"
                                >
                                    <component
                                        :is="
                                            TablerIcons[
                                                page.props?.details.status.icon
                                            ]
                                        "
                                        :size="20"
                                        :stroke="2"
                                    />
                                </div>
                                <div class="text-sm uppercase">
                                    {{ page.props?.details.status.name }}
                                </div>
                            </div>
                        </div>
                        <Divider align="left">
                            <span class="text-xs font-medium"
                                >Scholar Menu</span
                            >
                        </Divider>
                    </div>
                    <div class="flex-3">
                        <Menu :model="tabs">
                            <template #item="{ item, props }">
                                <a
                                    v-ripple
                                    :class="[
                                        'flex items-center gap-2 px-3 py-2 cursor-pointer',
                                        selectedTab.key == item.key
                                            ? 'hover:bg-slate-100'
                                            : '',
                                    ]"
                                    @click="changeMenu(item)"
                                >
                                    <component
                                        :is="TablerIcons[item.icon]"
                                        :size="18"
                                    ></component>
                                    <span class="!text-xs">{{
                                        item.label
                                    }}</span>
                                    <Badge
                                        v-if="item.badge"
                                        size="small"
                                        severity="danger"
                                        class="ml-auto !text-xs"
                                        :value="item.badge"
                                    />
                                </a>
                            </template>
                        </Menu>
                    </div>
                </div>
                <div class="lg:flex-7">
                    <Panel
                        class="!rounded-xl"
                        v-if="selectedTab.key == 1"
                        :pt="{
                            header: '!border-b-1 !border-gray-300 !border-dashed !p-0',
                        }"
                    >
                        <template #header>
                            <div class="p-2 w-full flex justify-between">
                                <div class="flex items-center gap-2">
                                    <Avatar
                                        class="rounded-full !bg-blue-100 !text-blue-500"
                                        size="small"
                                    >
                                        <IconUserQuestion
                                            :size="20"
                                            stroke-width="2"
                                        />
                                    </Avatar>
                                    <h3 class="text-lg font-bold text-nowrap">
                                        Personal Records
                                    </h3>
                                </div>
                                <div class="flex w-full justify-end">
                                    <DefaultButton
                                        :icon="TablerIcons.IconUserEdit"
                                        label="Edit Details"
                                        size="small"
                                        v-if="!editBtn.info"
                                        @click="editBtn.info = true"
                                        raised
                                        class-name="!rounded-xl !px-5"
                                    />
                                    <div class="flex items-center gap-2" v-else>
                                        <DefaultButton
                                            :icon="TablerIcons.IconUserCancel"
                                            label="Cancel Edit"
                                            size="small"
                                            severity="danger"
                                            @click="editBtn.info = false"
                                            outlined
                                            class-name="!rounded-xl !px-5"
                                        />
                                        <DefaultButton
                                            :icon="TablerIcons.IconUserCheck"
                                            raised
                                            label="Save this details"
                                            size="small"
                                            class-name="!rounded-xl !px-5"
                                        />
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template #default>
                            <div class="w-full flex flex-col pt-5 gap-3">
                                <div class="flex items-center gap-3">
                                    <TextInput
                                        v-model="personalInfo.first_name"
                                        label="First Name"
                                        :disabled="!editBtn.info"
                                    ></TextInput>

                                    <TextInput
                                        v-model="personalInfo.middle_name"
                                        label="Middle Name"
                                        :disabled="!editBtn.info"
                                    ></TextInput>
                                </div>
                                <div class="flex items-center gap-3">
                                    <TextInput
                                        v-model="personalInfo.last_name"
                                        label="Last Name"
                                        :disabled="!editBtn.info"
                                    ></TextInput>
                                    <TextInput
                                        v-model="personalInfo.suffix"
                                        label="Suffix"
                                        :disabled="!editBtn.info"
                                    >
                                    </TextInput>
                                </div>
                                <div class="flex items-center gap-2">
                                    <TextInput
                                        v-model="personalInfo.email"
                                        label="Email"
                                        :disabled="!editBtn.info"
                                    >
                                    </TextInput>
                                    <TextInput
                                        v-model="personalInfo.contact_no"
                                        label="Contact No"
                                        :disabled="!editBtn.info"
                                    ></TextInput>
                                </div>
                                <div class="flex items-center gap-2">
                                    <DatePickerInput
                                        v-model="personalInfo.birth_date"
                                        label="Birth Date"
                                        :disabled="!editBtn.info"
                                    ></DatePickerInput>
                                    <TextInput
                                        v-model="personalInfo.birth_place"
                                        label="Birth Place"
                                        :disabled="!editBtn.info"
                                    ></TextInput>
                                </div>
                                <div class="flex items-center gap-2">
                                    <TextInput
                                        v-model="personalInfo.religion"
                                        label="Religion"
                                        capitalize
                                        :disabled="!editBtn.info"
                                    ></TextInput>
                                    <TextInput
                                        v-model="personalInfo.civil_status"
                                        capitalize
                                        label="Civil Status"
                                        :disabled="!editBtn.info"
                                    >
                                    </TextInput>
                                </div>
                                <TextInput
                                    v-model="personalInfo.address"
                                    label="Address"
                                    :disabled="!editBtn.info"
                                    placeholder="Street, Subdivision, etc."
                                ></TextInput>
                                <AutoCompleteInput
                                    v-model="personalInfo.fulladdress"
                                    :options="page.props?.resultSearch"
                                    :disabled="!editBtn.info"
                                    :loading="loading.address"
                                    placeholder="Find by Barangay, Municipality, Province, or Region"
                                    @complete="autoSearch"
                                    selection
                                ></AutoCompleteInput>

                                <Divider align="left">
                                    <span class="text-xs font-semibold"
                                        >Scholarship Assignment</span
                                    >
                                </Divider>
                                <div class="flex items-center gap-3">
                                    <SelectInput
                                        label="Scholar Program"
                                        v-model="personalInfo.program"
                                        :options="page.props?.programOptions"
                                    ></SelectInput>

                                    <SelectInput
                                        label="Scholar Sub-Program"
                                        v-model="personalInfo.sub_program"
                                        :options="page.props?.subProgramOptions"
                                    ></SelectInput>
                                </div>
                                <div class="flex items-center gap-3">
                                    <TextInput
                                        v-model="personalInfo.last_name"
                                        label="Award Year"
                                        :disabled="!editBtn.info"
                                    ></TextInput>
                                    <TextInput
                                        v-model="personalInfo.suffix"
                                        label="Status"
                                        :disabled="!editBtn.info"
                                    >
                                    </TextInput>
                                </div>
                                <Divider align="left">
                                    <span class="text-xs font-semibold"
                                        >Guardian Information</span
                                    >
                                </Divider>
                                <div class="flex items-center gap-3">
                                    <TextInput
                                        v-model="personalInfo.last_name"
                                        label="Program"
                                        :disabled="!editBtn.info"
                                    ></TextInput>
                                    <TextInput
                                        v-model="personalInfo.suffix"
                                        label="Sub-Program"
                                        :disabled="!editBtn.info"
                                    >
                                    </TextInput>
                                </div>
                                <div class="flex items-center gap-3">
                                    <TextInput
                                        v-model="personalInfo.last_name"
                                        label="Award Year"
                                        :disabled="!editBtn.info"
                                    ></TextInput>
                                    <TextInput
                                        v-model="personalInfo.suffix"
                                        label="Status"
                                        :disabled="!editBtn.info"
                                    >
                                    </TextInput>
                                </div>
                            </div>
                        </template>
                    </Panel>
                </div>
            </div>
        </template>
    </Drawer>
</template>
<script setup>
import { router, useForm, usePage } from "@inertiajs/vue3";
import {
    IconCopy,
    IconId,
    IconUserFilled,
    IconAt,
    IconMapPin,
    IconUser,
    IconSchool,
    IconBuildingEstate,
    IconUserQuestion,
} from "@tabler/icons-vue";
import * as TablerIcons from "@tabler/icons-vue";
import { ref, watch } from "vue";
import DefaultButton from "../../Components/buttons/DefaultButton.vue";
import TextInput from "../../Components/inputs/TextInput.vue";
import DatePickerInput from "../../Components/inputs/DatePickerInput.vue";
import AutoCompleteInput from "../../Components/inputs/AutoCompleteInput.vue";
import SelectInput from "../../Components/inputs/SelectInput.vue";
const page = usePage();
const modelValue = defineModel("modelValue");
const selectedTab = ref({
    label: "Personal Records",
    icon: "IconUserQuestion",
    key: 1,
});
const editBtn = ref({
    info: false,
    tr: false,
    stipend: false,
    activity: false,
});
const loading = ref({
    address: false,
});

const personalInfo = useForm({
    last_name: null,
    first_name: null,
    middle_name: null,
    suffix: null,
    email: null,
    contact_no: null,
    birth_date: null,
    birth_place: null,
    religion: null,
    civil_status: null,
    address: null,
    fulladdress: null,
    rogram: null,
    sub_program: null,
    award_year: null,
    status: null,
});

const tabs = ref([
    { label: "Personal Records", icon: "IconUserQuestion", key: 1 },
    {
        separator: true,
    },
    {
        label: "Transcript of Records",
        icon: "IconScript",
        badge: page.props?.details?.tr_request,
        key: 2,
    },
    {
        separator: true,
    },
    { label: "Stipend", icon: "IconCoins", key: 3 },
    {
        separator: true,
    },
    { label: "Activit logs", icon: "IconWood", key: 4 },
]);

const changeMenu = (item) => {
    selectedTab.value = item;
};

const autoSearch = (event) => {
    router.get(
        route("scholars"),
        { findAddress: event },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ["resultSearch"],
        },
    );
};
watch(
    () => page.props?.details,
    (newVal) => {
        console.log(newVal);
        personalInfo.last_name = newVal.lname ?? null;
        personalInfo.first_name = newVal.fname ?? null;
        personalInfo.middle_name = newVal.mname ?? null;
        personalInfo.suffix = newVal.suffix ?? null;
        personalInfo.email = newVal.email ?? null;
        personalInfo.contact_no = newVal.contact_no ?? null;
        personalInfo.birth_date = newVal.birthdate
            ? new Date(newVal.birthdate)
            : null;
        personalInfo.birth_place = newVal.birthplace ?? null;
        personalInfo.religion = newVal.religion ?? null;
        personalInfo.civil_status = newVal.civil_status ?? null;
        personalInfo.address = newVal.address?.address ?? null;
        personalInfo.fulladdress = newVal.fullAddress ?? null;
    },
    { immediate: true },
);
</script>
