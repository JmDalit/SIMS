<template>
    <Head title="Scholars" />
    <AuthLayout>
        <div class="flex flex-col w-full h-full gap-5">
            <div class="flex flex-col lg:flex-row items-center space-x-0 gap-4">
                <HeaderModule
                    title="Scholar Management"
                    description="Comprehensive records of all scholars, including profile details, program assignments, and status monitoring."
                />
            </div>
            <div class="w-full flex items-end justify-between">
                <div class="flex items-center gap-2">
                    <IconTextInput
                        :icon="TablerIcons.IconSearch"
                        placeholder="Search keywords..."
                        v-model="searchInput"
                        class="w-64 lg:w-96"
                    />
                    <div>
                        <DefaultButton
                            :icon="
                                filterSchool != null
                                    ? TablerIcons.IconFilterFilled
                                    : TablerIcons.IconFilter
                            "
                            label="Schools"
                            class-name="w-30  !rounded-xl"
                            size="small"
                            severity="secondary"
                            @click="toggleOpSchool"
                        />
                        <Popover ref="opSchool">
                            <div
                                class="gap-3 flex"
                                v-if="page.props?.schoolOptions"
                            >
                                <div class="flex-1 w-60">
                                    <SelectMultiInput
                                        filter
                                        v-model="filterSchool"
                                        :options="page.props?.schoolOptions"
                                        capitalize
                                    ></SelectMultiInput>
                                </div>

                                <div
                                    class="flex justify-end items-center gap-2"
                                >
                                    <DefaultButton
                                        @click="schoolFilterClear"
                                        label="Clear"
                                        class-name="w-20 !rounded-xl"
                                        size="small"
                                        severity="secondary"
                                    />
                                    <DefaultButton
                                        @click="schoolFilter"
                                        label="Filter"
                                        class-name="w-20 !rounded-xl"
                                        size="small"
                                    />
                                </div>
                            </div>
                        </Popover>
                    </div>
                    <div>
                        <DefaultButton
                            :icon="
                                filterProgram != null
                                    ? TablerIcons.IconFilterFilled
                                    : TablerIcons.IconFilter
                            "
                            label="Programs"
                            class-name="w-30  !rounded-xl"
                            size="small"
                            severity="secondary"
                            @click="toggleopProgram"
                        />
                        <Popover ref="opProgram">
                     
                            <div
                                class="gap-3 flex"
                                v-if="page.props?.programOptions"
                            >
                          
                                <div class="flex-1 w-60">
                                    <SelectMultiInput
                                     
                                        v-model="filterProgram"
                                        :options="page.props?.programOptions"
                                        capitalize
                                    ></SelectMultiInput>
                                </div>

                                <div
                                    class="flex justify-end items-center gap-2"
                                >
                                    <DefaultButton
                                        @click="programFilterClear"
                                        label="Clear"
                                        class-name="w-20 !rounded-xl"
                                        size="small"
                                        severity="secondary"
                                    />
                                    <DefaultButton
                                        @click="programFilter"
                                        label="Filter"
                                        class-name="w-20 !rounded-xl"
                                        size="small"
                                    />
                                </div>
                            </div>
                        </Popover>
                    </div>
                    <div>
                        <DefaultButton
                            :icon="
                                filterSub != null
                                    ? TablerIcons.IconFilterFilled
                                    : TablerIcons.IconFilter
                            "
                            label="Sub-Programs"
                            class-name=" !rounded-xl"
                            size="small"
                            severity="secondary"
                            @click="toggleoSub"
                        />
                        <Popover ref="opSub">
                            <div
                                class="gap-3 flex"
                                v-if="page.props?.SubProgramOptions"
                            >
                                <div class="flex-1 w-60">
                                    <SelectMultiInput
                                       
                                        v-model="filterSub"
                                        :options="page.props?.SubProgramOptions"
                                        capitalize
                                    ></SelectMultiInput>
                                </div>

                                <div
                                    class="flex justify-end items-center gap-2"
                                >
                                    <DefaultButton
                                        @click="subFilterClear"
                                        label="Clear"
                                        class-name="w-20 !rounded-xl"
                                        size="small"
                                        severity="secondary"
                                    />
                                    <DefaultButton
                                        @click="subFilter"
                                        label="Filter"
                                        class-name="w-20 !rounded-xl"
                                        size="small"
                                    />
                                </div>
                            </div>
                        </Popover>
                    </div>
                    <div>
                        <DefaultButton
                            :icon="
                                filterSchool != null
                                    ? TablerIcons.IconFilterFilled
                                    : TablerIcons.IconFilter
                            "
                            label="Status"
                            class-name=" !rounded-xl"
                            size="small"
                            severity="secondary"
                            @click="toggleOpSchool"
                        />
                        <Popover ref="opSchool">
                            <div
                                class="gap-3 flex"
                                v-if="page.props?.schoolOptions"
                            >
                                <div class="flex-1 w-60">
                                    <SelectMultiInput
                                        filter
                                        v-model="filterSchool"
                                        :options="page.props?.schoolOptions"
                                        capitalize
                                    ></SelectMultiInput>
                                </div>

                                <div
                                    class="flex justify-end items-center gap-2"
                                >
                                    <DefaultButton
                                        @click="schoolFilterClear"
                                        label="Clear"
                                        class-name="w-20 !rounded-xl"
                                        size="small"
                                        severity="secondary"
                                    />
                                    <DefaultButton
                                        @click="schoolFilter"
                                        label="Filter"
                                        class-name="w-20 !rounded-xl"
                                        size="small"
                                    />
                                </div>
                            </div>
                        </Popover>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <DefaultButton
                        label="Requests"
                        class-name="w-30  !rounded-xl"
                        size="small"
                        severity="secondary"
                        :badge="page.props?.request_cnt"
                    />
                    <DefaultButton
                        :icon="TablerIcons.IconPlus"
                        label="Create"
                        class-name="w-30  !rounded-xl"
                        size="small"
                        raised
                    />
                </div>
            </div>
            <DefaultSelectionTable
                :items="page.props.scholars.data"
                :pagination="{
                    total: page.props.scholars.total,
                    perPage: page.props.scholars.per_page,
                    currentPage: page.props.scholars.current_page,
                }"
                @selected="toggleScholarDetails"
                :loading="loading.table"
                @paginate="loadPage"
            >
                <Column header="Scholars">
                    <template #body="props">
                        <div class="flex items-center gap-2">
                            <div class="">
                                <OverlayBadge
                                    severity="danger"
                                    class="inline-flex"
                                    v-if="props.data.subjectRequest_cnt"
                                >
                                    <Avatar
                                        :label="
                                            props.data.fullname
                                                .charAt(0)
                                                .toUpperCase()
                                        "
                                        style="
                                            background-color: #dee9fc;
                                            color: #1a2551;
                                        "
                                        class="!w-[40px] !h-[40px] !rounded-xl"
                                        :image="
                                            props.data.photo == null
                                                ? null
                                                : props.data.photo
                                        "
                                    />
                                </OverlayBadge>
                                <Avatar
                                    v-else
                                    :label="
                                        props.data.fullname
                                            .charAt(0)
                                            .toUpperCase()
                                    "
                                    style="
                                        background-color: #dee9fc;
                                        color: #1a2551;
                                    "
                                    class="!w-[40px] !h-[40px] !rounded-xl"
                                    :image="
                                        props.data.photo == null
                                            ? null
                                            : props.data.photo
                                    "
                                />
                            </div>
                            <div class="flex-1 flex flex-col">
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
                <Column header="Program / Sub Program">
                    <template #body="props">
                        <div class="flex items-center gap-1">
                            <div class="flex items-center">
                                <p>
                                    {{ props.data.program }}
                                </p>
                            </div>
                            <div>-</div>
                            <div class="flex items-center">
                                <p>
                                    {{ props.data.type }}
                                </p>
                            </div>
                        </div>
                    </template>
                </Column>
                <Column header="Region">
                    <template #body="props">
                        <div class="uppercase font-medium">
                            {{ props.data.agency }}
                        </div>
                    </template>
                </Column>

                <Column>
                    <template #header>
                        <div class="flex justify-center w-full font-semibold">
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
                        <div class="flex justify-center w-full font-semibold">
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
                                    :is="TablerIcons[props.data.status.icon]"
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
    </AuthLayout>
</template>
<script setup>
import { computed, reactive, ref, watch } from "vue";
import AuthLayout from "../../Layouts/AuthLayout.vue";
import * as TablerIcons from "@tabler/icons-vue";
import HeaderModule from "../../Modules/Others/HeaderModule.vue";
import DefaultSelectionTable from "../../Components/tables/DefaultSelectionTable.vue";
import { Head, usePage, router } from "@inertiajs/vue3";
import TextInput from "../../Components/inputs/TextInput.vue";
import IconTextInput from "../../Components/inputs/IconTextInput.vue";
import DefaultButton from "../../Components/buttons/DefaultButton.vue";
import { route } from "ziggy-js";
import { event } from "@primeuix/themes/aura/timeline";
import SelectMultiInput from "../../Components/inputs/SelectMultiInput.vue";
const page = usePage();
const loading = reactive({
    table: false,
});
const opSchool = ref(null);
const opProgram = ref(null);
const opSub = ref(null)
const filterSchool = ref(page.props?.filterSchool ?? [] );
const filterProgram = ref(null);
const filterSub = ref(null)
const searchInput = ref( page.props?.filterSearch ?? null);
const timerBounce = ref(null);

const loadPage = (page) => {
    console.log(filterSchool)
    router.get(
        route("scholars"),
        {
            page,
            ...(searchInput.value ? { search: searchInput.value } : {}),
            ...(filterSchool.value ? { schools: filterSchool.value } : {}),
            ...(filterProgram.value ? { programs: filterProgram.value } : {}),
                        ...(filterSub.value ? { sub: filterSub.value } : {}),
        },
        {
            preserveState: true,
            preserveScroll: true,
            onBefore: () => (loading.table = true),
            onFinish: () => (loading.table = false),
        },
    );
};

const toggleScholarDetails = (event) => {
    router.reload({
        only: ["details"],
        data: { id: event.id },
        preserveState: true,
        onSuccess: () => {},
    });
};

const toggleOpSchool = (event) => {
    opSchool.value.toggle(event);
    router.reload({
        only: ["schoolOptions"],
    });
};

const schoolFilter = (event) => {
    opSchool.value.toggle(event);
    console.log(filterSchool.value )
    clearTimeout(timerBounce.value);
    timerBounce.value = setTimeout(() => {
        loadPage(1);
    }, 300);
};

const schoolFilterClear = (event) => {
    opSchool.value.toggle(event);
    filterSchool.value = null;
    clearTimeout(timerBounce.value);
    timerBounce.value = setTimeout(() => {
        loadPage(1);
    }, 300);
};

const toggleopProgram = (event) => {
    opProgram.value.toggle(event);
    router.reload({
        only: ["programOptions"],
    });
};

const programFilter = (event) => {
    opProgram.value.toggle(event);
    clearTimeout(timerBounce.value);
    timerBounce.value = setTimeout(() => {
        loadPage(1);
    }, 300);
};

const programFilterClear = (event) => {
    opProgram.value.toggle(event);
    filterProgram.value = null;
    clearTimeout(timerBounce.value);
    timerBounce.value = setTimeout(() => {
        loadPage(1);
    }, 300);
};

const toggleoSub = (event) => {
    opSub.value.toggle(event);
    router.reload({
        only: ["SubProgramOptions"],
    });
};

const subFilter = (event) => {
    opSub.value.toggle(event);
    clearTimeout(timerBounce.value);
    timerBounce.value = setTimeout(() => {
        loadPage(1);
    }, 300);
};

const subFilterClear = (event) => {
    opSub.value.toggle(event);
    filterSub.value = null;
    clearTimeout(timerBounce.value);
    timerBounce.value = setTimeout(() => {
        loadPage(1);
    }, 300);
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
