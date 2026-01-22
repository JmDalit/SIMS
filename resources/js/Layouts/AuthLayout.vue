<template>
    <div class="flex h-screen overflow-hidden dark:bg-gray-800 dark:text-white">

        <Transition name="slide-fade">
            <aside :class="[
                'z-30 fixed md:block md:relative md:flex-shrink-0 flex flex-col transition-all duration-300 bg-slate-100 dark:bg-gray-700 m-3 h-min-screen rounded-[15px] shadow-sm py-4 ',
                sidebar ? 'w-60 ' : 'w-20 ',
            ]">
                <div :class="[
                    'h-full w-full flex flex-col justify-between px-[1.2rem] ',
                ]">
                    <div class="flex gap-2 items-center top-0 sticky">
                        <div class="">
                            <Image src="/images/seilogo.png" alt="Logo" width="39" height="39" />
                        </div>

                        <div v-show="sidebar"
                            class="font-semibold flex-1 text-blue-600 dark:!text-blue-400 leading-none">
                            STSIMS
                            <span class="block text-xs text-gray-500">DOST-SEI</span>
                        </div>
                    </div>

                    <nav :class="[
                        'flex flex-col h-[80%] ',
                        !sidebar ? 'items-center' : '',
                    ]">
                        <SidebarIconMenu :list="page.props.menu" v-if="!sidebar" />
                        <SidebarLabelMenu :list="page.props.menu" class="overflow-x-hidden" v-else />
                    </nav>
                    <div :class="[
                        sidebar ? 'flex justify-center' : 'flex w-full',
                    ]"></div>
                </div>
            </aside>
        </Transition>

        <div class="flex-1 flex flex-col w-full md:py-3 md:pr-3">
            <header class="bg-blue-600 h-16 p-3 md:rounded-[15px] text-white w-full">
                <div class="flex justify-between items-center w-full h-full">
                    <div class="flex-1">
                        <DefaultButton size="small" variant="text" class="!text-white hover:!bg-transparent"
                            @click="toggleSidebar" :icon="sidebar ? IconChevronLeft : IconChevronRight" rounded />
                    </div>
                    <div class="flex items-center gap-2">
                        <DefaultToggle v-model="isDark" @update-value="toggleDark" :un-check-icon="IconSun"
                            :check-icon="IconMoon" />
                        <DefaultButton variant="text" class="!text-white hover:!bg-transparent" :icon="IconBell"
                            size="lg" rounded />
                        <HeadBarButtonMenu />
                    </div>
                </div>
            </header>
            <main class="overflow-auto flex-1 p-2">
                <slot />
            </main>
        </div>
    </div>
</template>
<script setup>
import SidebarIconMenu from "../Components/menus/SidebarIconMenu.vue";
import DefaultButton from "../Components/buttons/DefaultButton.vue";
import HeadBarButtonMenu from "../Components/menus/HeadBarButtonMenu.vue";
import {
    IconDashboard,
    IconCategory,
    IconUsers,
    IconServerSpark,
    IconUserShield,
    IconMap,
    IconCornerDownRight,
    IconSchool,
    IconMenu2,
    IconBuildings,
    IconBooks,
    IconListDetails,
    IconChevronLeft,
    IconChevronRight,
    IconSun,
    IconMoon,
    IconBell,
} from "@tabler/icons-vue";
import { ref, onMounted, Transition } from "vue";
import SidebarLabelMenu from "../Components/menus/SidebarLabelMenu.vue";
import DefaultToggle from "../Components/toggleswitches/DefaultToggle.vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const isDark = ref(false);
const sidebar = ref(false);
const menuList = [
    {
        label: "Dashboard",
        slug: "dashboard",
        icons: IconDashboard,
        route: "/dashboard",
    },
    {
        label: "Academic Data",
        slug: "academic-data",
        active: "acadamic-data",
        key: "Records",
        icons: IconSchool,
        items: [
            {
                label: "Universities",
                icons: IconBuildings,
                route: "/records/schools",
                subItem: true,
                component: "records/schools",
            },
            {
                label: "Courses",
                icons: IconBooks,
                route: "/records/courses",
                subItem: true,
                component: "records/courses",
            },
            {
                label: "Settings",
                icons: IconListDetails,
                route: "/records/dropdown",
                subItem: true,
                component: "records/dropdowns",
            },
        ],
    },
    {
        label: "Places",
        slug: "places",
        icons: IconMap,
        active: "places",
        items: [
            {
                label: "Regions",
                icons: IconCornerDownRight,
                route: "/places/regions",

                subItem: true,
            },
            {
                label: "Provinces",
                icons: IconCornerDownRight,
                route: "/places/provinces",

                subItem: true,
            },
            {
                label: "Cities",
                icons: IconCornerDownRight,
                route: "/places/municipalities",

                subItem: true,
            },
            {
                label: "Barangay",
                icons: IconCornerDownRight,
                route: "/places/barangay",

                subItem: true,
            },
        ],
    },

    {
        label: "Role Management",
        slug: "role-management",
        icons: IconUserShield,
        route: "/roles",
    },
    {
        label: "Menu Management",
        slug: "Routes",
        icons: IconCategory,
        route: "/routes",
    },
    {
        label: "User Management",
        slug: "user-management",
        icons: IconUsers,
        route: "/scholars",
    },
    {
        label: "Backup & Restore",
        slug: "user-management",
        icons: IconServerSpark,
        route: "/scholars",
    },
];
function toggleDark() {
    localStorage.setItem("theme", isDark.value ? "dark" : "light");
    applyTheme();
}

const toggleSidebar = () => {
    localStorage.setItem("sidebar", !sidebar.value);
    sidebar.value = !sidebar.value;
};

function applyTheme() {
    document.documentElement.classList.toggle("dark", isDark.value);
}

const toggleMobileSidebar = () => {
    isMobileSidebarOpen.value = !isMobileSidebarOpen.value;
};

onMounted(() => {
    isDark.value = localStorage.getItem("theme") === "dark";
    applyTheme();
    sidebar.value = localStorage.getItem("sidebar") === "true";
});
</script>
<style scoped>
.slide-fade {
    transition: transform 0.3s ease, opacity 0.3s ease;
}

.slide-fade-hide {
    transform: translateX(-100%);
    opacity: 1;
}
</style>
