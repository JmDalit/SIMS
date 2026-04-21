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
            <div class="flex flex-col lg:flex-row w-full pt-5 h-full">
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
                                        selectedTab == item.key
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
                <div class="lg:flex-7"></div>
            </div>
        </template>
    </Drawer>
</template>
<script setup>
import { usePage } from "@inertiajs/vue3";
import {
    IconCopy,
    IconId,
    IconUserFilled,
    IconAt,
    IconMapPin,
    IconUser,
    IconSchool,
    IconBuildingEstate,
} from "@tabler/icons-vue";
import * as TablerIcons from "@tabler/icons-vue";
import { ref, watch } from "vue";
const page = usePage();
const modelValue = defineModel("modelValue");
const selectedTab = ref(1);
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
    selectedTab.value = item.key;
};
</script>
