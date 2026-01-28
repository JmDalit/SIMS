<template>
    <Button
        type="button"
        @click="toggle"
        size="small"
        variant="text"
        class="text-white py-1 px-2 rounded-[20px] cursor-pointer"
        unstyled
    >
        <div class="flex items-center gap-2">
            <Avatar
                v-if="page.props.user.profile.avatar === null"
                :label="page.props.user.email.charAt(0).toUpperCase()"
                style="background-color: #dee9fc; color: #1a2551"
                shape="circle"
            />

            <Avatar
                v-else
                style="background-color: #dee9fc; color: #1a2551"
                shape="circle"
                :image="page.props.user.profile.avatar_url"
            />

            <div class="flex-1 text-left leading-none">
                <div class="text-[12px] font-semibold leading-none">
                    {{
                        page.props.user.profile.fullname ??
                        page.props.user.email
                    }}
                </div>
                <span class="text-[10px] leading-none capitalize">
                    {{ page.props.user.role_array.name }} (<span
                        class="uppercase"
                        >{{ page.props.user.profile.agency.slug }}</span
                    >)</span
                >
            </div>
        </div>
    </Button>
    <div class="flex-col justify-center text-white">
        <Menu ref="menu" :model="items" class="!mt-2" :popup="true">
            <template #submenulabel="{ item }">
                <span class="text-sm">{{ item.label }}</span>
            </template>
            <template #item="{ item, props }">
                <a
                    v-ripple
                    class="flex items-center p-2 cursor-pointer !text-xs gap-2"
                    type="button"
                    @click="item.action"
                >
                    <component
                        :is="item.icons"
                        size="25px"
                        :class="
                            item.label === 'Logout'
                                ? 'text-red-500'
                                : 'text-gray-600'
                        "
                        :stroke-width="1.5"
                    />
                    <span
                        class="text-gray-500"
                        :class="
                            item.label === 'Logout'
                                ? 'text-red-500'
                                : 'text-gray-600'
                        "
                        >{{ item.label }}</span
                    >
                </a>
            </template>
        </Menu>
    </div>
    <!-- <DefaultDialog
        class="absolute"
        v-model:visible="openModal"
        @submit-form="submitForm()"
        :icons="IconPasswordUser"
        title="Update Your Password"
        caption="Keep your account secure by choosing a strong, unique password."
    >
        <template #popup-message>
            <messageComponent
                v-show="passwordForm.hasErrors"
                :message="passwordForm.errors"
                message-type="error"
                :icons="IconExclamationCircleFilled"
            ></messageComponent>
        </template>
        <div class="flex flex-col gap-5">
            <inputComponent
                label="Current Password"
                type="password"
                id="opass"
                with-eye
                v-model="passwordForm.current"
            ></inputComponent>
            <inputComponent
                label="New Password"
                type="password"
                id="npass"
                v-model="passwordForm.new"
            ></inputComponent>
            <inputComponent
                label="Confirm Password"
                id="cpass"
                type="password"
                v-model="passwordForm.confirm"
            ></inputComponent>
        </div>
    </DefaultDialog> -->
</template>

<script setup>
import { ref } from "vue";
import { router, usePage, useForm } from "@inertiajs/vue3";
import {
    IconUserCircle,
    IconLogout2,
    IconLockPassword,
    IconPasswordUser,
    IconExclamationCircleFilled,
} from "@tabler/icons-vue";

const page = usePage();
// const openModal = ref(false);

// const passwordForm = useForm({
//     current: "",
//     new: "",
//     confirm: "",
// });

const menu = ref();
const items = ref([
    {
        label: "Options",
        items: [
            {
                label: "Profile",
                icons: IconUserCircle,
                action: () => {
                    openProfile();
                },
            },
            {
                label: "Change Password",
                icons: IconPasswordUser,
                action: () => {
                    openDialog();
                },
            },
            {
                label: "Lock Screen",
                icons: IconLockPassword,
                action: () => {
                    lockScreen();
                },
            },
            {
                separator: true,
            },
            {
                label: "Logout",
                icons: IconLogout2,
                action: () => {
                    logout();
                },
            },
        ],
    },
]);

const toggle = (event) => {
    menu.value.toggle(event);
};

// const openDialog = () => {
//     openModal.value = true;
//     passwordForm.reset();
//     passwordForm.clearErrors();
// };

// const submitForm = () => {
//     passwordForm.put(route("changepassword.update"), {
//         onSuccess: (res) => {
//             var result = res.props.result;

//             toast.add({
//                 severity: result.status,
//                 summary: "Success",
//                 detail: result.message,
//                 group: "bc",
//                 life: 3000,
//             });

//             openModal.value = false;
//             passwordForm.reset();
//         },
//     });
// };

// const openProfile = () => {
//     router.get(route("profile.index"));
// };

// const lockScreen = () => {
//     router.get(route("locked.show"));
// };

const logout = () => {
    router.post(route("logout"));
};
</script>
<style>
.p-menu {
    min-width: 150px !important;
}
</style>
