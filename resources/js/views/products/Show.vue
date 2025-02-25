<script setup>
import {onMounted, ref} from "vue";
import {useAuthStore} from "@/stores/auth.js";
import productsApi from "@/api/products.js";
import {toast} from "vue3-toastify";
import {useRoute, useRouter} from "vue-router";
import MainLayout from "@/components/layouts/MainLayout.vue";
import Update from "@/views/products/Update.vue";

const isAdmin = useAuthStore().isAdmin
const product = ref('')

const route = useRoute()
const router = useRouter()
onMounted(async () => {
    try {
        const response = await productsApi.show(route.params.id)
        product.value = response.data.data.product
    } catch (err) {
        toast(`Oops ${err}`, {
            "theme": "auto",
            "type": "error",
            "autoClose": 2000,
        })
    }
})
const destroy = async () => {
    try {
        const response = await productsApi.deleteProduct(product.value.id)
        if (response.status === 200) {
            await router.push('/products')
            toast(`Deleted`, {
                "theme": "auto",
                "type": "info",
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
    <MainLayout>
        <template #content>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <h1 class="text-2xl font-bold text-white mb-6">Product</h1>
                <div class="flex justify-between product-card items-center">
                    <div class="rounded-lg">
                        <img src="/images/cat.jpg"
                             alt="Random Cat for {{ product.name }}"
                             class="h-48 w-full object-cover rounded-t-lg">
                        <div class="p-4">
                            <h2 class="text-lg font-semibold text-gray-200">{{ product.name }}</h2>
                            <p class="text-gray-600 mt-2">{{ product.description }}</p>
                            <div class="mt-4 flex justify-between items-center">
                            <span
                                class="text-xl font-bold text-gray-200">${{ product.price }}</span>
                                <form v-if="isAdmin" @submit.prevent="destroy">
                                    <button type="submit" onclick="confirm('Are you sure to delete?')"
                                            class="text-indigo-600 hover:underline">
                                        <i class="fa-solid fa-trash" style="color: #ff2e2e;"></i>
                                    </button>
                                </form>
                                <div v-else class="mt-3 flex items-center justify-between space-x-2">
                                    <button>
                                        <i class="fa-solid fa-cart-shopping" style="color: #ff2e2e;"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="isAdmin" class="px-4 sm:px-6 lg:px-8 py-6 w-1/2">
                        <h1 class="text-2xl font-bold text-white mb-6">Edit</h1>
                        <Update :product="product"/>
                    </div>
                </div>
            </div>
        </template>
    </MainLayout>
</template>
