<script setup>
import Guest from "@/components/layouts/Guest.vue";
import PrimaryButton from "@/components/navigation/PrimaryButton.vue";
import authAPI from "@/api/auth.js";
import {toast} from "vue3-toastify";
import {ref} from "vue";

const data = ref({
    email: null
})
const sendForgotReq = async () => {
    try {
        const response = await authAPI.forgotPassword(data.value)

        toast(response.data.message, {
            "theme": "auto",
            "type": "info",
            "autoClose": 2000,
        })
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
        <form @submit.prevent="sendForgotReq">
            <div>
                <h1 class="text-xl text-center font-bold text-white">We will send a link to reset your password!</h1>
                <label for="email" class="text-white">Email</label>
                <input v-model="data.email"
                       class="block border-gray-300 text-white font-bold rounded bg-transparent mt-1 w-full"
                       type="email" required
                       autofocus autocomplete="email"/>
            </div>
            <div class="flex items-center justify-end mt-4">
                <PrimaryButton type="submit" name="Send Link"/>
            </div>
        </form>
    </Guest>
</template>
