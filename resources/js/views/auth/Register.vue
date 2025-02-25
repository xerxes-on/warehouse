<script setup>

import PrimaryButton from "@/components/navigation/PrimaryButton.vue";
import Guest from "@/components/layouts/Guest.vue";
</script>

<template>
    <Guest>
        <template #content>
            <form>
                <div>
                    <label for="name" class="text-white">Name</label>
                    <input id="name" class="block mt-1 w-full" type="text" name="name" required
                           autofocus autocomplete="name"/>
                </div>
                <div>
                    <label for="email" class="text-white">Email</label>
                    <input id="email" class="block mt-1 w-full" type="email" name="email" required
                           autofocus autocomplete="email"/>
                </div>
                <div class="mt-4">
                    <label for="password" class="text-white">Password</label>
                    <input id="password" class="block mt-1 w-full"
                           type="password"
                           name="password"
                           required autocomplete="current-password"/>
                </div>
                <div class="mt-4">
                    <label for="password_confirmation" class="text-white">Password Confirmation</label>

                    <input id="password_confirmation" class="block mt-1 w-full"
                           type="password"
                           name="password_confirmation" required autocomplete="new-password"/>
                </div>
                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                       href="/forgotpassword">
                        Forgot your password?
                    </a>
                    <PrimaryButton @click.prevent="login()" name="Log in"/>
                </div>
            </form>
        </template>
    </Guest>
</template>
