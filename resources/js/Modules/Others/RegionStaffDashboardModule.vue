<template>
    <div class="flex h-full w-full items-center justify-center">
        <div
            class="grid h-full w-full gap-4 p-2 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 lg:grid-rows-5"
        >
            <div
                class="bg-gray-50 rounded-2xl shadow-lg flex flex-col lg:col-span-1 lg:row-span-1 p-3"
            >
                <div class="flex items-start">
                    <div class="flex-1 font-semibold text-gray-600 text-sm">
                        Undergraduate Scholars
                    </div>
                    <div class="">
                        <Avatar
                            class="!bg-blue-100 !text-blue-600 !rounded-2xl border border-blue-600"
                            size="large"
                            ><IconUserFilled size="30"></IconUserFilled
                        ></Avatar>
                    </div>
                </div>
                <div class="flex-1 flex items-center gap-2">
                    <span class="text-3xl font-light text-gray-500">0</span>
                    <div
                        class="flex items-center bg-green-100 rounded-2xl text-green-600 text-xs px-3 gap-1"
                    >
                        <IconTrendingUp :size="20" />
                        <div>This year</div>
                    </div>
                </div>
                <div class="flex items-center gap-1">
                    <div class="text-xs text-gray-500">Total last year</div>
                    <div>0</div>
                </div>
            </div>

            <div
                class="bg-gray-50 rounded-2xl shadow-lg flex flex-col lg:col-span-1 lg:row-span-1 p-3"
            >
                <div class="flex items-start">
                    <div class="flex-1 font-semibold text-gray-600 text-sm">
                        JLSS Scholars
                    </div>
                    <div class="">
                        <Avatar
                            class="!bg-purple-100 !text-purple-600 !rounded-2xl border border-purple-600"
                            size="large"
                            ><IconUserFilled size="30"></IconUserFilled
                        ></Avatar>
                    </div>
                </div>
                <div class="flex-1 flex items-center gap-2">
                    <span class="text-3xl font-light text-gray-500">0</span>
                    <div
                        class="flex items-center bg-green-100 rounded-2xl text-green-600 text-xs px-3 gap-1"
                    >
                        <IconTrendingUp :size="20" />
                        <div>This year</div>
                    </div>
                </div>
                <div class="flex items-center gap-1">
                    <div class="text-xs text-gray-500">Total last year</div>
                    <div>0</div>
                </div>
            </div>

            <!-- <div
                class="bg-gray-50 rounded-2xl shadow-lg flex flex-col lg:col-span-1 lg:row-span-1 p-3"
            >
                <div class="flex items-start">
                    <div class="flex-1 font-semibold text-gray-600 text-sm">
                        Registered campuses
                    </div>
                    <div class="">
                        <Avatar
                            class="!bg-indigo-100 !text-indigo-600 !rounded-2xl border border-indigo-600"
                            size="large"
                            ><IconBuildingCommunity size="30" />
                        </Avatar>
                    </div>
                </div>
                <div class="flex-1 flex items-center gap-2">
                    <span class="text-3xl font-light text-gray-500">{{
                        campus_cnt
                    }}</span>
                    <div
                        class="flex items-center bg-blue-100 rounded-2xl text-blue-600 text-xs px-3 gap-1"
                    >
                        <div class="capitalize">total</div>
                    </div>
                </div>
                <div class="flex items-center gap-1">
                    <div class="text-xs text-gray-500 uppercase">
                        {{ user.profile.agency.slug }}
                    </div>
                </div>
            </div> -->

            <div
                class="rounded-2xl border border-slate-200 flex flex-col md:col-span-2 lg:col-span-2 lg:row-span-3"
            >
                <div class="flex items-start p-3">
                    <div
                        class="flex-11 font-semibold flex flex-col text-gray-600 text-sm"
                    >
                        <div>Campus status</div>
                        <div class="text-sm text-gray-400 font-light">
                            <span class="font-bold">{{ campus_cnt }}</span>
                            in total
                        </div>
                    </div>
                    <div class="flex-1">
                        <Avatar
                            class="!bg-indigo-100 !text-indigo-600 !rounded-2xl border border-indigo-600"
                            size="large"
                            ><IconDeviceDesktopAnalytics
                                size="30"
                            ></IconDeviceDesktopAnalytics
                        ></Avatar>
                    </div>
                </div>

                <div class="flex-1 p-2 overflow-auto">
                    <table class="w-full table-fixed table">
                        <thead>
                            <tr class="">
                                <th
                                    class="!w-[20%] text-start text-xs font-semibold text-gray-700"
                                >
                                    Campuses
                                </th>
                                <th
                                    class="w-[20%] text-xs font-semibold text-gray-700 text-center"
                                >
                                    Semesters
                                </th>
                                <th
                                    class="w-[20%] text-xs font-semibold text-gray-700 text-center"
                                >
                                    Deadlines
                                </th>
                                <th
                                    class="w-[20%] text-xs font-semibold text-gray-700 text-center"
                                >
                                    Grading Status
                                </th>
                                <th
                                    class="w-[10%] text-xs font-semibold text-gray-700 text-end"
                                >
                                    Programs No.
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(item, index) in campuses_details"
                                :key="index"
                                :class="[
                                    'border-b border-gray-300 w-full',
                                    index == 0 ? 'border-t' : '',
                                ]"
                            >
                                <td
                                    class="!py-[0.2rem] text-xs items-center flex text-gray-700"
                                >
                                    <span v-tooltip.top="item.generated_name">{{
                                        item.school_shortcut
                                    }}</span>
                                </td>

                                <td class="!py-[0.2rem] text-xs text-gray-700">
                                    <div
                                        class="flex w-full items-center justify-center"
                                    >
                                        {{ item.semesters.type?.name }}
                                    </div>
                                    <div
                                        class="flex w-full items-center justify-center text-blue-400"
                                        v-show="item.semesters.length != 0"
                                    >
                                        {{ item.semesters.start_date }} -
                                        {{ item.semesters.end_date }}
                                    </div>
                                </td>
                                <td
                                    class="!py-[0.2rem] text-xs font-semibold text-center text-gray-700"
                                >
                                    {{ item.semesters.summision }}
                                </td>
                                <td
                                    class="!py-[0.2rem] font-normal flex h-full items-center w-full"
                                >
                                    <div
                                        class="flex w-full h-full items-center justify-center"
                                    >
                                        <div
                                            :class="[
                                                item.grading_status
                                                    ? 'bg-green-50 text-green-600 border border-green-600'
                                                    : 'bg-red-50 text-red-600 border border-red-600',
                                                'px-3 rounded-2xl py-1 flex items-center  gap-1 ',
                                            ]"
                                        >
                                            <IconX
                                                size="15"
                                                v-if="!item.grading_status"
                                            />
                                            <IconCheck v-else size="15" />
                                            <div class="text-xs">
                                                {{
                                                    item.grading_status
                                                        ? "Grading Set"
                                                        : "Not Set"
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td
                                    class="!py-[0.2rem] text-sm font-bold text-end text-gray-700"
                                >
                                    {{ item.program_cnt }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div
                class="rounded-2xl border border-slate-200 flex flex-col md:col-span-2 lg:col-span-1 lg:row-span-3"
            >
                <div class="flex flex-col">
                    <VCalendar
                        transparent
                        borderless
                        class="py-1"
                        expanded
                        view="weekly"
                        :attributes="attrs"
                    ></VCalendar>
                    <Divider align="left">
                        <p class="text-xs font-medium">Event Scheduler</p>
                    </Divider>
                </div>
                <div class="flex-1 px-2">
                    <div
                        class="w-full h-full flex-col scroll-auto overflow-auto"
                    ></div>
                </div>
            </div>
            <!-- <div
                class="bg-green-200 rounded-lg shadow-md flex items-center justify-center md:col-span-2 lg:col-span-2 lg:row-span-2"
            >
                <p>Edamame</p>
            </div>

            <div
                class="bg-red-200 rounded-lg shadow-md flex items-center justify-center md:col-span-1 lg:col-span-2 lg:row-span-2"
            >
                <p>Tomato</p>
            </div>

            <div
                class="bg-gray-200 rounded-lg shadow-md flex items-center justify-center md:col-span-2 lg:col-span-3 lg:row-span-2"
            >
                <p>Tofu</p>
            </div> -->
        </div>
    </div>
</template>
<script setup>
import { usePage } from "@inertiajs/vue3";
import {
    IconBuildingCommunity,
    IconCheck,
    IconDeviceDesktopAnalytics,
    IconTrendingUp,
    IconUser,
    IconUserFilled,
    IconX,
} from "@tabler/icons-vue";

import { ref } from "vue";
import DefaultScrollTable from "../../Components/tables/DefaultScrollTable.vue";

const { campus_cnt, user, campuses_details } = usePage().props;
const attrs = ref([
    {
        key: "today",
        highlight: true,
        dates: new Date(),
    },
]);
</script>
<style>
.vc-container .vc-weekday-1,
.vc-container .vc-weekday-7 {
    color: #6366f1;
    margin-bottom: 0.3rem;
}
.vc-day {
    color: #828283;
}
.vc-title-wrapper .vc-title {
    color: #707070 !important;
}
</style>
