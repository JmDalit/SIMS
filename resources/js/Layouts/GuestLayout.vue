<template>
    <main class="!h-full min-w-screen flex flex-col lg:flex-row bg-[#ffffff] dark:text-gray-300 dark:bg-gray-800">
        <div class="w-[100%] !min-h-screen lg:w-[50%] xl:w-[40%] flex flex-col">
            <!-- <div class="w-full p-2 lg:fixed lg:top-0">
                <div class="flex items-center gap-2">
                    <Avatar size="large" image="/images/seilogo.png" />

                    <div class="flex-1">
                        <div class="font-semibold text-xs xl:text-base">
                            Department of Science and Technology
                        </div>
                        <div class="text-[11px] lg:text-sm">
                            SCIENCE EDUCATION INSTITUTE
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="flex-1 p-2 flex h-full items-center justify-center">
                <div class="w-[70%]">
                    <slot />
                </div>
            </div>
        </div>
        <div class="w-[65%] hidden lg:block relative overflow-hidden bg-cover"
            style="background-image: url('/images/backgroundLogin.png')">
            <div class="flex w-full h-full items-center justify-center">
                <img src="../../../public/images/login_img.png" alt="Background" class="w-[80%] object-cover" />
            </div>
        </div>
    </main>
</template>
<script setup>
import { onMounted, ref } from "vue";

const isDark = ref(false);

function applyTheme() {
    document.documentElement.classList.toggle("dark", isDark.value);
}

onMounted(() => {
    isDark.value = localStorage.getItem("theme") === "dark";
    applyTheme();
});
</script>
