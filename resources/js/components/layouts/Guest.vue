<script setup>
import {onMounted} from "vue";
import {useAuthStore} from "@/stores/auth.js";
import {useRouter} from "vue-router";

const router = useRouter()
onMounted(() => {
    if (useAuthStore().isAuthorized) {
        router.push('home')
    }
})
</script>

<template>
    <div
        class="font-sans text-gray-900 antialiased min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div
            class="w-full sm:max-w-md mt-6 px-10 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-xl">
            <div class="absolute w-64 h-64 bg-white/10 rounded-full top-1/4 left-1/4 animate-float"></div>
            <div class="absolute w-48 h-48 bg-white/5 rounded-full bottom-1/4 right-1/4 animate-float-delay"></div>
            <div class="absolute w-32 h-32 bg-white/10 rounded-full bottom-1/3 left-1/3 animate-pulse-slow"></div>
            <slot name="content"></slot>
        </div>
    </div>
</template>

<style scoped>
@keyframes float {
    0% {
        transform: translateY(0) rotate(0deg);
    }
    50% {
        transform: translateY(-20px) rotate(5deg);
    }
    100% {
        transform: translateY(0) rotate(0deg);
    }
}

@keyframes float-delay {
    0% {
        transform: translateY(15px) rotate(0deg);
    }
    50% {
        transform: translateY(-15px) rotate(-5deg);
    }
    100% {
        transform: translateY(0) rotate(0deg);
    }
}

.animate-float {
    animation: float 1s ease-in-out infinite;
}

.animate-float-delay {
    animation: float 3s ease-in-out 2s infinite;
}

.animate-pulse-slow {
    animation: float 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
