<template>
    <DefaultDrawer v-model:visible="drawer" size="!w-[50rem]">
        <template #header>
            <div class="flex items-center gap-2">
                <div class="">
                    <Avatar
                        v-if="details.school.photo == null"
                        :label="details.school.name.charAt(0).toUpperCase()"
                        style="background-color: #dee9fc; color: #1a2551"
                        shape="circle"
                        class="!w-[50px] !h-[50px] font-extrabold !text-2xl"
                    />

                    <Avatar
                        v-else
                        style="background-color: #dee9fc; color: #1a2551"
                        shape="circle"
                        :image="details.school.photo"
                        class="!w-[50px] !h-[50px]"
                    />
                </div>

                <div class="flex flex-col">
                    <div class="font-bold">
                        {{ details.fullname_campus }}
                    </div>
                    <div class="text-xs flex items-center gap-1 text-gray-500">
                        <IconMapPin size="18" />
                        <div>
                            {{ details.address.full_address.name }}
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template #body>
            <div class="flex items-center justify-end px-5">
                <DefaultButton
                    size="small"
                    label="Update"
                    :icon="IconPencil"
                    :icon-size="18"
                    raised
                    class-name="!rounded-xl w-30 !text-xs"
                />
            </div>
            <div class="flex flex-col gap-5 px-5 py-2">
                <div class="flex justify-between gap-2">
                    <div class="w-[50%] flex items-center gap-2">
                        <div class="bg-slate-200 p-2 shadow rounded-xl">
                            <IconUserStar size="20" />
                        </div>
                        <div class="flex flex-col flex-1">
                            <div
                                class="text-xs font-semibold flex items-center"
                            >
                                <div>DEAN:</div>
                            </div>
                            <div class="text-sm font-light">
                                John Doe Reverse
                            </div>
                        </div>
                    </div>
                    <div class="w-[50%] flex items-center gap-2">
                        <div class="bg-slate-200 p-2 shadow rounded-xl">
                            <IconUserQuestion size="20" />
                        </div>
                        <div class="flex flex-col flex-1">
                            <div
                                class="text-xs font-semibold flex items-center"
                            >
                                <div>REGISTRAR:</div>
                            </div>
                            <div class="text-sm font-light">
                                John Doe Reverse
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between gap-2">
                    <div class="w-[50%] flex items-center gap-2">
                        <div class="bg-slate-200 p-2 shadow rounded-xl">
                            <IconPhone size="20" />
                        </div>
                        <div class="flex flex-col flex-1">
                            <div
                                class="text-xs font-semibold flex items-center"
                            >
                                <div>CONTACT NO:</div>
                            </div>
                            <div class="text-sm font-light">973123613236</div>
                        </div>
                    </div>
                    <div class="w-[50%] flex items-center gap-2">
                        <div class="bg-slate-200 p-2 shadow rounded-xl">
                            <IconAt size="20" />
                        </div>
                        <div class="flex flex-col flex-1">
                            <div
                                class="text-xs font-semibold flex items-center"
                            >
                                <div>EMAIL:</div>
                            </div>
                            <div class="text-sm font-light">
                                JohnDoe@gmail.com
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between gap-2">
                    <div class="w-[50%] flex items-center gap-2">
                        <div class="bg-slate-200 p-2 shadow rounded-xl">
                            <IconWorldWww size="20" />
                        </div>
                        <div class="flex flex-col flex-1">
                            <div
                                class="text-xs font-semibold flex items-center"
                            >
                                <div>VISIT PAGE:</div>
                            </div>
                            <div class="text-sm font-light">dost.gov.ph</div>
                        </div>
                    </div>
                </div>
            </div>
            <Divider align="left" type="dashed" class="!m-0">
                <span class="text-xs font-semibold">Courses</span>
            </Divider>
            <div class="px-5 py-2 gap-5 flex flex-col">
                <ToolbarModule
                    v-model="searchInput"
                    @deleteSearch="clearSearch"
                    @saveForm="submitForm('courses')"
                    button-label="Create"
                    :dialog-title="
                        !courseForm.id ? 'Create Course' : 'Edit Course'
                    "
                    :dialog-description="
                        !courseForm.id
                            ? 'Provide the necessary information to add a new course to the system.'
                            : 'Modify the existing course information and save your changes.'
                    "
                    :dialog-button-loading="courseForm.processing"
                    :dialog-icon="IconBook2"
                    dialog-button-label="Save"
                    :message-has-errors="courseForm.hasErrors"
                    :message-errors="courseForm.errors"
                    @buttonOpenModal="
                        toggleModal({ type: 'create', class: 'course' })
                    "
                    message-type="error"
                    ref="toolbarCourseRef"
                >
                    <template #form>
                        <div class="flex flex-col gap-3 mt-5 mb-2">
                            <SelectInput
                                v-model="courseForm.course"
                                label="Course"
                                :options="courseOption"
                                clearable
                                filter
                            >
                            </SelectInput>
                            <TextInput
                                v-model="courseForm.years"
                                label="Years"
                            ></TextInput>
                        </div>
                        <Divider type="dashed" />
                        <div class="overflow-y-auto max-h-120 px-2">
                            <div class="flex items-center justify-between">
                                <div class="font-semibold">Subjects</div>
                            </div>
                            <div
                                class="flex flex-col gap-2 mt-4"
                                v-for="(item, index) in courseForm.subjects"
                                :key="index"
                            >
                                <div class="flex justify-between items-center">
                                    <div
                                        class="text-xs bg-blue-100 dark:text-gray-700 w-fit font-semibold px-2 py-1 rounded-lg"
                                    >
                                        <span> {{ index + 1 }}# Subject</span>
                                    </div>

                                    <Button
                                        v-show="
                                            index != 0 &&
                                            (hideRemoveButton == 'create' ||
                                                courseForm.subjects[index].id ==
                                                    null)
                                        "
                                        size="small"
                                        class="!text-xs"
                                        rounded
                                        severity="danger"
                                        outlined
                                        @click="removeSubject(index)"
                                    >
                                        <div class="flex items-center gap-2">
                                            <IconCircleXFilled
                                                size="20"
                                            ></IconCircleXFilled>
                                            <div>Remove</div>
                                        </div>
                                    </Button>
                                </div>
                                <div class="flex flex-col gap-3">
                                    <SelectInput
                                        v-model="
                                            courseForm.subjects[index].class
                                        "
                                        label="Class"
                                        :options="subClassOption"
                                        clearable
                                    >
                                    </SelectInput>
                                    <TextInput
                                        v-model="
                                            courseForm.subjects[index].name
                                        "
                                        label="Description"
                                        capitalize
                                    ></TextInput>
                                    <div class="flex items-center gap-3">
                                        <TextInput
                                            v-model="
                                                courseForm.subjects[index].code
                                            "
                                            label="Code"
                                        ></TextInput>

                                        <TextInput
                                            v-model="
                                                courseForm.subjects[index].unit
                                            "
                                            label="Unit"
                                        ></TextInput>
                                    </div>
                                </div>
                                <Divider type="dashed" />
                            </div>
                            <Button
                                size="small"
                                class="!text-xs !rounded-xl"
                                fluid
                                @click="addSubject"
                            >
                                <div class="flex items-center gap-2">
                                    <IconCirclePlusFilled
                                        size="18"
                                    ></IconCirclePlusFilled>
                                    <div>Add Subject</div>
                                </div>
                            </Button>
                        </div>
                    </template>
                </ToolbarModule>
                <DefaultScrollTable :items="details.courses">
                    <Column header="Name" field="course.name">
                        <template #body="props">
                            {{ props.data.course.name }}
                        </template>
                    </Column>
                    <Column field="course.abbreviation">
                        <template #header>
                            <div
                                class="flex justify-center w-full font-semibold"
                            >
                                Abbreviation
                            </div>
                        </template>
                        <template #body="props">
                            <div class="flex w-full justify-center">
                                {{ props.data.course.abbreviation }}
                            </div>
                        </template>
                    </Column>
                    <Column field="course.years">
                        <template #header>
                            <div
                                class="flex justify-center w-full font-semibold"
                            >
                                Years
                            </div>
                        </template>
                        <template #body="props">
                            <div
                                class="flex w-full justify-center font-extrabold"
                            >
                                {{ props.data.years }}
                            </div>
                        </template>
                    </Column>

                    <Column>
                        <template #body="slotProps">
                            <div class="flex justify-end">
                                <Button
                                    text
                                    v-tooltip.top="'Options'"
                                    rounded
                                    size="small"
                                    severity="secondary"
                                    icon="pi pi-ellipsis-v"
                                    @click="
                                        (e) =>
                                            toggleCourseOption(
                                                e,
                                                slotProps.data
                                            )
                                    "
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
                </DefaultScrollTable>
            </div>
            <Divider align="left" type="dashed" class="!m-0">
                <span class="text-xs font-semibold">Gradings</span>
            </Divider>
            <div class="px-5 py-2 gap-5 flex flex-col">
                <ToolbarModule
                    v-model="searchInput"
                    @deleteSearch="clearSearch"
                    @saveForm="submitForm('grades')"
                    button-label="Create"
                    :dialog-title="
                        !gradeForm.id ? 'Create Grade' : 'Edit Grade'
                    "
                    dialog-description="Define a new role and configure its access permissions."
                    :dialog-button-loading="gradeForm.processing"
                    :dialog-icon="IconUserCog"
                    dialog-button-label="Save"
                    :message-has-errors="gradeForm.hasErrors"
                    :message-errors="gradeForm.errors"
                    @buttonOpenModal="
                        toggleModal({ type: 'create', class: 'grades' })
                    "
                    message-type="error"
                    ref="toolbarGradeRef"
                >
                    <template #form>
                        <div class="flex flex-col gap-5 mt-5">
                            <TextInput
                                v-model="gradeForm.grade"
                                label="Grade"
                            ></TextInput>
                            <div class="flex gap-5 items-center">
                                <TextInput
                                    v-model="gradeForm.lower"
                                    label="Lower Limit"
                                ></TextInput>
                                <TextInput
                                    v-model="gradeForm.upper"
                                    label="Upper Limit"
                                ></TextInput>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <Divider type="dashed" />
                            <div class="flex justify-between items-center">
                                <div class="text-sm">
                                    Is it a failing grade?
                                </div>

                                <DefaultToggle
                                    v-model="gradeForm.fail"
                                    :check-icon="IconCheck"
                                    :un-check-icon="IconX"
                                    @update-value="
                                        gradeForm.fail
                                            ? (gradeForm.incomplete = false)
                                            : gradeForm.incomplete
                                    "
                                />
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <Divider type="dashed" />
                            <div class="flex justify-between items-center">
                                <div class="text-sm">
                                    Is it an incomplete grade?
                                </div>

                                <DefaultToggle
                                    v-model="gradeForm.incomplete"
                                    :check-icon="IconCheck"
                                    :un-check-icon="IconX"
                                    @update-value="
                                        gradeForm.incomplete
                                            ? (gradeForm.fail = false)
                                            : gradeForm.fail
                                    "
                                />
                            </div>
                        </div>
                    </template>
                </ToolbarModule>
                <DefaultScrollTable :items="details.grades">
                    <Column header="Description">
                        <template #body="props">
                            <div class="flex items-center">
                                <div v-if="props.data.is_failed">
                                    <div
                                        class="font-semibold flex items-center gap-1 text-red-600 px-4 rounded-xl"
                                    >
                                        <IconCircleX
                                            size="20"
                                            stroke-width="2"
                                        />
                                        <div>FAILED</div>
                                    </div>
                                </div>
                                <div v-else-if="props.data.is_incomplete">
                                    <div
                                        class="font-semibold flex items-center gap-1 text-yellow-600 px-4 rounded-xl"
                                    >
                                        <IconDotsCircleHorizontal
                                            size="20"
                                            stroke-width="2"
                                        />
                                        <div>INCOMPLETE</div>
                                    </div>
                                </div>
                                <div v-else>
                                    <div
                                        class="font-semibold flex items-center gap-1 text-green-600 px-4 rounded-xl"
                                    >
                                        <IconCircleCheck
                                            size="20"
                                            stroke-width="2"
                                        />
                                        <div>PASSED</div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </Column>
                    <Column class="text-center">
                        <template #header>
                            <div class="flex w-full justify-center">
                                <div class="text-xs font-bold">Grade</div>
                            </div>
                        </template>
                        <template #body="props">
                            <div class="flex justify-center font-semibold">
                                <div>{{ props.data.grade }}</div>
                            </div>
                        </template>
                    </Column>
                    <Column>
                        <template #header>
                            <div class="flex w-full justify-center">
                                <div class="text-xs font-bold">
                                    Grading Range
                                </div>
                            </div>
                        </template>
                        <template #body="props">
                            <div class="flex items-center justify-center">
                                <div
                                    v-if="
                                        props.data.lower != null ||
                                        props.data.upper != null
                                    "
                                >
                                    <span>{{ props.data.lower }}</span>
                                    -
                                    <span>{{ props.data.upper }}</span>
                                </div>
                                <div v-else>
                                    <div class="text-xs italic text-gray-400">
                                        No set
                                    </div>
                                </div>
                            </div>
                        </template>
                    </Column>
                    <Column>
                        <template #body="slotProps">
                            <div class="flex justify-end">
                                <Button
                                    text
                                    v-tooltip.top="'Options'"
                                    rounded
                                    size="small"
                                    severity="secondary"
                                    icon="pi pi-ellipsis-v"
                                    @click="
                                        (e) =>
                                            toggleGradeOption(e, slotProps.data)
                                    "
                                />
                                <Menu
                                    ref="menuGrade"
                                    :model="gradeMenuItems"
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
                </DefaultScrollTable>
            </div>
        </template>
    </DefaultDrawer>

    <DefaultDialog
        v-model:visible="subjectDialog"
        hide-footer
        :icon="IconBook2"
        width-set="lg:!w-[80%]"
        :title="selectedRow?.course?.name"
        description="View all subjects offered under this course, including their codes, units, and classifications."
    >
        <template #forms>
            <div class="my-5">
                <DefaultScrollTable :items="selectedRow?.subjects">
                    <Column header="Class" field="subject_class.name"> </Column>
                    <Column
                        header="Description"
                        class="capitalize"
                        field="name"
                        style="width: 30%"
                    >
                    </Column>
                    <Column
                        header="Code"
                        class="uppercase"
                        field="subject_code"
                    >
                    </Column>
                    <Column header="Unit" field="unit"> </Column>
                    <Column
                        header="Created By"
                        class="text-xs"
                        field="created_by"
                    >
                    </Column>
                    <Column
                        header="Created At"
                        class="text-xs"
                        field="formatted_date"
                    >
                    </Column>

                    <Column
                        header="Verified By"
                        class="text-xs"
                        field="verified_by"
                    >
                    </Column>
                    <Column
                        header="Verified At"
                        class="text-xs"
                        field="verified_at"
                    >
                    </Column>
                    <Column>
                        <template #header>
                            <div class="w-full flex justify-end mr-1.5">
                                <IconSettings size="20" />
                            </div>
                        </template>
                        <template #body="props">
                            <div class="flex w-full justify-end gap-2">
                                <DefaultButton
                                    severity="danger"
                                    size="small"
                                    rounded
                                    text
                                    :icon="IconTrashX"
                                    @click="
                                        deleteRow({
                                            id: props.data.id,
                                            type: 'subject',
                                        })
                                    "
                                >
                                </DefaultButton>
                            </div>
                        </template>
                    </Column>
                </DefaultScrollTable>
            </div>
        </template>
    </DefaultDialog>
    <DefaultToast ref="toastRef" />
</template>
<script setup>
import {
    IconCirclePlusFilled,
    IconPencil,
    IconTrashX,
    IconUserCog,
    IconMapPin,
    IconUserStar,
    IconUserQuestion,
    IconPhone,
    IconAt,
    IconWorldWww,
    IconBook2,
    IconCircleXFilled,
    IconCheck,
    IconX,
    IconPencilCog,
    IconTrash,
    IconBooks,
    IconSettings,
    IconCircleX,
    IconDotsCircleHorizontal,
    IconCircleCheck,
} from "@tabler/icons-vue";

import DefaultScrollTable from "../../Components/tables/DefaultScrollTable.vue";
import DefaultDrawer from "../../Components/dialogs/DefaultDrawer.vue";
import DefaultDialog from "../../Components/dialogs/DefaultDialog.vue";
import TextInput from "../../Components/inputs/TextInput.vue";
import SelectInput from "../../Components/inputs/SelectInput.vue";
import DefaultToast from "../../Components/messages/DefaultToast.vue";
import DefaultButton from "../../Components/buttons/DefaultButton.vue";
import ToolbarModule from "./ToolbarModule.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import DefaultToggle from "../../Components/toggleswitches/DefaultToggle.vue";

const props = defineProps({
    id: [Number, String],
    value: [Array, Object],
    courseOption: [Array, Object],
    subClassOption: [Array, Object],
    confirmRef: Object,
});

const page = usePage();
const details = computed(
    () =>
        page.props.universities?.data?.find(
            (u) => Number(u.id) === Number(props.id)
        ) ?? props.value
);
const drawer = ref(false);
const subjectDialog = ref(false);
const typeDialog = ref(null);
const searchInput = ref(null);
const toastRef = ref(null);
const toolbarCourseRef = ref(null);
const toolbarGradeRef = ref(null);
const selectedRow = ref(null);
const menu = ref(null);
const menuGrade = ref(null);
const hideRemoveButton = ref("create");
const courseForm = useForm({
    id: null,
    campusId: null,
    course: null,
    years: null,
    subjects: [
        {
            id: null,
            name: null,
            code: null,
            class: null,
            unit: null,
        },
    ],
});

const gradeForm = useForm({
    id: null,
    campusId: null,
    grade: null,
    upper: null,
    lower: null,
    fail: false,
    incomplete: false,
});

const toggleCourseOption = (event, rowData) => {
    selectedRow.value = rowData;
    menu.value.toggle(event);
};

const toggleGradeOption = (event, rowData) => {
    selectedRow.value = rowData;
    menuGrade.value.toggle(event);
};

const addSubject = () => {
    courseForm.subjects.push({
        id: null,
        name: null,
        code: null,
        class: null,
        unit: null,
    });
};

const removeSubject = (index) => {
    if (index == 0) {
        return;
    }
    courseForm.subjects.splice(index, 1);
};

const clearSearch = () => {
    searchInput.value = null;
};

const openDrawer = () => {
    drawer.value = true;
};

const toggleModal = (res) => {
    courseForm.resetAndClearErrors();
    typeDialog.value = res.class;
    hideRemoveButton.value = res.type;
    courseForm.campusId = props.value.id;
    gradeForm.campusId = props.value.id;
    if (res.type === "edit" && res.class == "course") {
        courseForm.subjects = [];
        courseForm.id = selectedRow.value.id;
        courseForm.course = selectedRow.value.course_array;
        courseForm.years = selectedRow.value.years;
        selectedRow.value.subjects.forEach((element) => {
            courseForm.subjects.push({
                id: element.id,
                name: element.name,
                code: element.subject_code,
                class: element.class_array,
                unit: element.unit,
            });
        });
    }
    if (res.type === "edit" && res.class == "grade") {
        gradeForm.id = selectedRow.value.id;
        gradeForm.grade = selectedRow.value.grade;
        gradeForm.upper = selectedRow.value.upper;
        gradeForm.lower = selectedRow.value.lower;
        gradeForm.fail = selectedRow.value.is_failed;
        gradeForm.incomplete = selectedRow.value.is_incomplete;
    }

    if (res.class == "course") {
        toolbarCourseRef.value.openModal();
    } else {
        toolbarGradeRef.value.openModal();
    }
};

const menuItems = computed(() => {
    if (!selectedRow.value) return [];

    return [
        {
            label: "View Subjects",
            icon: IconBooks,
            class: "text-gray-600",
            command: () => {
                subjectDialog.value = true;
            },
        },
        {
            label: "Edit",
            icon: IconPencilCog,
            class: "text-gray-600",
            command: () => {
                toggleModal({
                    type: "edit",
                    class: "course",
                    data: selectedRow.value,
                });
            },
        },
        {
            label: "Delete",
            icon: IconTrash,
            class: "text-red-500",
            command: () => {
                deleteRow({ id: selectedRow.value.id, type: "course" });
            },
        },
    ];
});

const gradeMenuItems = computed(() => {
    if (!selectedRow.value) return [];

    return [
        {
            label: "Edit",
            icon: IconPencilCog,
            class: "text-gray-600",
            command: () => {
                toggleModal({
                    type: "edit",
                    class: "grade",
                    data: selectedRow.value,
                });
            },
        },
        {
            label: "Delete",
            icon: IconTrash,
            class: "text-red-500",
            command: () => {
                deleteRow({ id: selectedRow.value.id, type: "grade" });
            },
        },
    ];
});

const submitForm = (res) => {
    if (res == "courses") {
        if (!courseForm.id) {
            courseForm.post(route("academic.universities.course.store"), {
                onSuccess: () => {
                    courseForm.resetAndClearErrors();
                    toastRef.value.show(page.props.flash);
                },
            });
        } else {
            courseForm.put(
                route("academic.universities.course.update", {
                    id: courseForm.id,
                    type: "form",
                }),
                {
                    onSuccess: () => {
                        toolbarCourseRef.value.closeModal();
                        courseForm.resetAndClearErrors();
                        toastRef.value.show(page.props.flash);
                    },
                }
            );
        }
    } else {
        if (!gradeForm.id) {
            gradeForm.post(route("academic.universities.grade.store"), {
                onSuccess: () => {
                    gradeForm.resetAndClearErrors();
                    toastRef.value.show(page.props.flash);
                },
            });
        } else {
            gradeForm.put(
                route("academic.universities.grade.update", {
                    id: gradeForm.id,
                    type: "form",
                }),
                {
                    onSuccess: () => {
                        toolbarCourseRef.value.closeModal();
                        gradeForm.resetAndClearErrors();
                        toastRef.value.show(page.props.flash);
                    },
                }
            );
        }
    }
};

const deleteRow = (res) => {
    switch (res.type) {
        case "subject":
            props.confirmRef.popupDialog(() => {
                router.delete(
                    route("subject.destroy", { id: res.id, type: "delete" }),
                    {
                        onSuccess: () => {
                            toastRef.value.show(page.props.flash);
                        },
                    }
                );
            });
            break;
        case "grade":
            props.confirmRef.popupDialog(() => {
                router.delete(
                    route("academic.universities.grade.destroy", {
                        id: res.id,
                        type: "delete",
                    }),
                    {
                        onSuccess: () => {
                            toastRef.value.show(page.props.flash);
                        },
                    }
                );
            });
            break;
        default:
            props.confirmRef.popupDialog(() => {
                router.delete(
                    route("academic.universities.course.destroy", {
                        id: res.id,
                        type: "delete",
                    }),
                    {
                        onSuccess: () => {
                            toastRef.value.show(page.props.flash);
                        },
                    }
                );
            });
            break;
    }
};

defineExpose({
    openDrawer,
});
</script>
