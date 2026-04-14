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
        </div>
        <DefaultSelectionTable
            :items="page.props.scholars.data"
            :pagination="{
                total: page.props.scholars.total,
                perPage: page.props.scholars.per_page,
                currentPage: page.props.scholars.current_page,
            }"
            :loading="loading.table"
            @paginate="loadPage"
        >
        </DefaultSelectionTable>
    </AuthLayout>
</template>
<script setup>
import { reactive } from "vue";
import AuthLayout from "../../Layouts/AuthLayout.vue";
import HeaderModule from "../../Modules/Others/HeaderModule.vue";
import DefaultSelectionTable from "../../Components/tables/DefaultSelectionTable.vue";
import { Head, usePage } from "@inertiajs/vue3";

const page = usePage();

const loading = reactive({
    table: false,
});

const loadPage = (page) => {
    router.get(
        route("scholars"),
        {
            page,
            ...(searchInput.value ? { search: searchInput.value } : {}),
        },
        {
            preserveState: true,
            preserveScroll: true,
            onBefore: () => (scholarLoadingTable.value = true),
            onFinish: () => (scholarLoadingTable.value = false),
        },
    );
};
</script>
