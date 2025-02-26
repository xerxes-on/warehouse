<script setup>
import PrimaryButton from "@/components/navigation/PrimaryButton.vue";
import Guest from "@/components/layouts/Guest.vue";
import authAPI from "@/api/auth.js";
import {toast} from "vue3-toastify";
import {ref} from "vue";
import {useAuthStore} from "@/stores/auth.js";
import {useRouter} from "vue-router";

const data = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})
const authStore = useAuthStore()
const router = useRouter()
const registerUser = async () => {
    try {
        const response = await authAPI.register(data.value)
        if (response.status === 200) {
            authStore.setUser(response.data.data.user)
            authStore.setToken(response.data.data.token)
            await router.push('home')
        } else {
            toast(response.message, {
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
        <template #content>
            <form @submit.prevent="registerUser">
                <div>
                    <label for="name" class="text-white">Name</label>
                    <input id="name" class="block mt-1 w-full" type="text" v-model="data.name" required
                           autofocus autocomplete="name"/>
                </div>
                <div>
                    <label for="email" class="text-white">Email</label>
                    <input id="email" class="block mt-1 w-full" type="email" v-model="data.email" required
                           autofocus autocomplete="email"/>
                </div>
                <div class="mt-4">
                    <label for="password" class="text-white">Password</label>
                    <input id="password" class="block mt-1 w-full"
                           type="password"
                           v-model="data.password"
                           required autocomplete="current-password"/>
                </div>
                <div class="mt-4">
                    <label for="password_confirmation" class="text-white">Password Confirmation</label>

                    <input id="password_confirmation" class="block mt-1 w-full"
                           type="password"
                           v-model="data.password_confirmation" required autocomplete="new-password"/>
                </div>
                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                       href="/forgot">
                        Forgot your password?
                    </a>
                    <PrimaryButton type="submit" name="Register"/>
                </div>
            </form>
        </template>
    </Guest>
</template>
