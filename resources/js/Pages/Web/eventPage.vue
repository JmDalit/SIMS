<template>
    <Head title="Event Controller" />
    <AuthLayout>
        <div class="flex flex-col lg:flex-row w-full h-full p-8">
            <div class="w-full lg:w-3/4 border-1 border-slate-200 rounded-2xl">
                dsadas
            </div>
        </div>
        <DefaultToast ref="toastRef" />
        <DefaultConfirmDialog ref="confirmRef" />
    </AuthLayout>
</template>
<script setup>
import { Head, router, useForm, usePage } from "@inertiajs/vue3";
import AuthLayout from "../../Layouts/AuthLayout.vue";
import { computed, ref, watch } from "vue";
import { IconPencilCog, IconTrash } from "@tabler/icons-vue";
import DefaultToast from "../../Components/messages/DefaultToast.vue";
import DefaultConfirmDialog from "../../Components/dialogs/DefaultConfirmDialog.vue";
const page = usePage();
</script>
