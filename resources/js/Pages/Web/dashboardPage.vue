<template>
    <Head title="Dashboard" />
    <AuthLayout>
        <div
            class="mx-auto"
            v-if="page.props.user?.role_array.name == 'Administrator'"
        >
            <h1 class="text-2xl font-bold mb-4">Welcome to the Dashboard</h1>
            <p class="text-gray-700">
                This is your dashboard where you can manage your activities.
            </p>
        </div>
        <div
            class="mx-auto flex flex-col"
            v-else-if="page.props.user?.role_array.name == 'regional staff'"
        >
            <div class="w-full flex items-center justify-between">
                <div class="flex-1">
                    <h1 class="text-xl font-semibold">Overview</h1>
                    <p class="text-sm text-gray-600">
                        A quick summary of key information, status, and recent
                        activity.
                    </p>
                </div>
            </div>
            <div class="flex-1">
                <RegionStaffDashboardModule />
            </div>
        </div>
    </AuthLayout>
</template>
<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import AuthLayout from "../../Layouts/AuthLayout.vue";
import RegionStaffDashboardModule from "../../Modules/Others/RegionStaffDashboardModule.vue";

import { ref } from "vue";
const page = usePage();
</script>
