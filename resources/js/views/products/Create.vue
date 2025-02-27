<script setup>
import {onMounted, ref} from "vue";
import {useAuthStore} from "@/stores/auth.js";
import {useRouter} from "vue-router";
import {toast} from "vue3-toastify";
import productsApi from "@/api/products.js";
import MainLayout from "@/components/layouts/MainLayout.vue";

const router = useRouter()
onMounted(() => {
    if (!useAuthStore().isAdmin) {
        router.push('/')
    }
})
const createProduct = async () => {
    try {
        const response = await productsApi.create({'name': name.value, 'price': price.value})
        if (response.status === 200) {
            toast('Created', {
                theme: "auto",
                type: "success",
                autoClose: 2000,
            });
        }
    } catch (e) {
        toast(`Oops ${e}`, {
            theme: "auto",
            type: "error",
            autoClose: 2000,
        });
    }
}
const name = ref()
const price = ref()
</script>

<template>
    <MainLayout>
        <div class="flex justify-center items-center">
            <form @submit.prevent="createProduct"
                  class="border border-gray-400 rounded-lg p-6 space-y-6 w-1/2 mt-10">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300">Product Name</label>
                    <input type="text" v-model="name"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                           required>
                </div>
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-300">Price</label>
                    <input type="number" v-model="price"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                           required min="1">
                </div>
                <div class="text-right">
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
                        Create
                    </button>
                </div>
            </form>
        </div>
    </MainLayout>
</template>

