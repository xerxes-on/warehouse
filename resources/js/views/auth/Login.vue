<script setup>

import PrimaryButton from "@/components/navigation/PrimaryButton.vue";
import Guest from "@/components/layouts/Guest.vue";
import {reactive} from "vue";
import authAPI from "@/api/auth.js";
import {useAuthStore} from "@/stores/auth.js";
import {useRouter} from "vue-router";
import {toast} from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

const data = reactive({
    email: '',
    password: '',
})
const authStore = useAuthStore()
const router = useRouter()
const login = async () => {
    try {
        const response = await authAPI.login(data)
        if (response.status === 200) {
            authStore.setUser(response.data.data.user)
            authStore.setToken(response.data.data.token)
            await router.push('home')
        } else {
            toast(response.data.message, {
                "theme": "auto",
                "type": "waring",
                "autoClose": 2000,
            })
        }
    } catch (err) {
        toast(`Oops ${err}`, {
            "theme": "auto",
            "type": "error",
            "autoClose": 2000,
        })
    }
}
</script>

<template>
    <Guest>
        <form @submit.prevent="login">
            <div>
                <label for="email" class="text-white">Email</label>
                <input v-model="data.email" id="email"
                       class="block border-gray-300 text-white font-bold rounded bg-transparent mt-1 w-full" type="email" required
                       autofocus autocomplete="email"/>
            </div>
            <div class="mt-4">
                <label for="password" class="text-white">Password</label>
                <input v-model="data.password" id="password"
                       class="border-gray-300 block  text-white font-bold bg-transparent mt-1 w-full rounded-md"
                       type="password"
                       name="password"
                       required autocomplete="current-password"/>
            </div>
            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                   href="/forgot-password">
                    Forgot your password?
                </a>
                <PrimaryButton type="submit" name="Log in"/>
            </div>
        </form>
    </Guest>
</template>
