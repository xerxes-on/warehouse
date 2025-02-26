<script setup>
import {onMounted, ref} from "vue";
import {useAuthStore} from "@/stores/auth.js";
import productsApi from "@/api/products.js";
import {toast} from "vue3-toastify";
import {useRoute, useRouter} from "vue-router";
import MainLayout from "@/components/layouts/MainLayout.vue";
import Product from "@/components/products/Product.vue";
import Update from "@/views/products/Update.vue";
import {useMainStore} from "@/stores/main.js";

const isAdmin = useAuthStore().isAdmin
const product = ref()
const products = ref()

const route = useRoute()
const router = useRouter()
onMounted(async () => {
    try {
        useMainStore().setLoading()
        const response = await productsApi.show(route.params.id)
        const responseIndex = await productsApi.index('/')

        if (!response || !responseIndex || response.status !== 200 || responseIndex.status !== 200) {
            toast(`${response?.data?.message || 'Error'}/${responseIndex?.data?.message || 'Error'}`, {
                theme: "auto",
                type: "warning",
                autoClose: 3000,
            })
            return
        }
        product.value = response.data.data.product
        products.value = responseIndex.data.data.products.data
        product.value.amount = 1
    } catch (err) {
        toast(`Oops ${err.message || err}`, {
            theme: "auto",
            type: "error",
            autoClose: 2000,
        })
    } finally {
        useMainStore().unsetLoading()
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
            <button @click="destroy" v-if="isAdmin"
                    class="text-gray-300 font-bold bg-amber-700 px-4 py-2 rounded-2xl mr-10">Delete
            </button>
            <div class="rounded-lg flex items-center justify-center p-2 shadow-xl relative">
                <Product v-if="products" :product-show="product" :products-all="products"/>
            </div>
            <div v-if="isAdmin" class="px-4 mx-auto sm:px-6 lg:px-8 py-6 w-1/2">
                <h1 class="text-2xl font-bold text-white mb-6">Edit</h1>
                <Update v-if="product" :product="product"/>
            </div>
        </template>
    </MainLayout>
</template>
