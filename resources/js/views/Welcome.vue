<script setup>

import Guest from "@/components/layouts/Guest.vue";
import {useAuthStore} from "@/stores/auth.js";

console.log(useAuthStore().isAuthorized)

</script>
<template>
    <Guest>
        <template #content>
            <div
                class=" p-15 rounded-xl shadow-2xl flex flex-col items-center gap-4 z-10">
                <h1 class="text-white text-2xl font-bold mb-4">Welcome</h1>
                <div class="w-fill flex justify-between items-center">
                    <RouterLink
                        to="login"
                        class="w-48 py-3 mr-5 px-6 bg-white/90 hover:bg-white text-indigo-700 font-semibold rounded-lg shadow-lg transform transition-all duration-300 hover:scale-105 text-center"
                    >
                        Login
                    </RouterLink>

                    <RouterLink
                        to="register"
                        class="w-48 py-3 px-6 bg-amber-700 hover:bg-indigo-600 text-white font-semibold rounded-lg shadow-lg transform transition-all duration-300 hover:scale-105 text-center"
                    >
                        Register
                    </RouterLink>
                </div>

            </div>
        </template>
    </Guest>
</template>

