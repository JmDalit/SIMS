<template>
    <Head title="Activate Account" />
    <main
        class="min-w-screen min-h-screen flex flex-col bak bg-[#ffffff] dark:text-gray-300 dark:bg-gray-800"
        style="background-image: url(&quot;/images/backgroundLogin.png&quot;)"
    >
        <div class="flex-1 flex w-full h-full items-center justify-center">
            <div
                class="bg-white dark:!bg-gray-800 p-10 rounded-2xl shadow w-[30%]"
            >
                <div class="flex flex-col gap-4">
                    <div
                        class="flex flex-col items-center mb-10 w-full text-slate-800"
                    >
                        <Avatar size="large" image="/images/seilogo.png" />
                        <div
                            class="font-semibold text-2xl md:text-[30px] text-gray-600 text-center antialiased dark:!text-gray-300 p-2"
                        >
                            Activate your
                            <span class="text-blue-600">Account!</span>
                            🎓
                        </div>
                        <div
                            class="w-full text-center text-[14px] text-gray-400"
                        >
                            Manage applications, track progress, and streamline
                            scholarship data — all in one place.
                        </div>
                    </div>
                    <form @submit.prevent="submitForm">
                        <div class="flex flex-col w-full gap-3">
                            <TextInput
                                label="Email"
                                disabled
                                v-model="loginForm.email"
                            ></TextInput>

                            <PasswordInput
                                label="Password"
                                v-model="loginForm.password"
                                :feedback="false"
                                toggle-icon
                            />
                            <PasswordInput
                                label="Confirm Password"
                                v-model="loginForm.password"
                                :feedback="false"
                            />
                            <DefaultButton
                                label="Update account"
                                size="small"
                                class="mt-5 w-full"
                                :loading="loginForm.processing"
                                :disabled="loginForm.processing"
                                raised
                            />
                        </div>
                    </form>
                    <div
                        class="flex justify-center mt-5 border-slate-300 border-dashed border-t-1 text-sm text-gray-400"
                    >
                        <div class="flex items-center mt-5">
                            <div>Already have an account?</div>
                            <DefaultButton
                                label="Login"
                                type="button"
                                text
                                size="small"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <OtpDialog
            v-model:visible="otpModal"
            :icon="IconPasswordUser"
            button-label="Send OTP"
        ></OtpDialog>
    </main>
</template>
<script setup>
import { IconPasswordUser, IconAlertCircle } from "@tabler/icons-vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import GuestLayout from "../../Layouts/GuestLayout.vue";
import TextInput from "../../Components/inputs/TextInput.vue";
import PasswordInput from "../../Components/inputs/PasswordInput.vue";
import DefaultButton from "../../Components/buttons/DefaultButton.vue";
import DefaultCheckbox from "../../Components/checkboxs/DefaultCheckbox.vue";
import OtpDialog from "../../Components/dialogs/OtpDialog.vue";
import { onMounted, ref } from "vue";
import DefaultMessages from "../../Components/messages/DefaultMessages.vue";

const page = usePage();

const loginForm = useForm({
    photo: null,
    fname: null,
    lname: null,
    email: page.props?.email ?? null,
    designation: null,
    agency: null,
    contact: null,
    password: null,
});

const submitForm = () => {
    loginForm.post(route("login.store"), {
        onSuccess: () => loginForm.reset(),
    });
};

const isDark = ref(false);

function applyTheme() {
    document.documentElement.classList.toggle("dark", isDark.value);
}

onMounted(() => {
    isDark.value = localStorage.getItem("theme") === "dark";
    applyTheme();
});
</script>
