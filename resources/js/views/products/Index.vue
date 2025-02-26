<script setup>
import MainLayout from "@/components/layouts/MainLayout.vue";
import {onMounted, ref} from "vue";
import {toast} from "vue3-toastify";
import productsApi from "@/api/products.js";
import {useAuthStore} from "@/stores/auth.js";
import 'vue3-toastify/dist/index.css';
import ProductSearch from "@/components/ProductSearch.vue";
import Product from "@/components/products/Product.vue";
import {useMainStore} from "@/stores/main.js";

const products = ref([]);
const nextPageNumber = ref(null);
const prevPageNumber = ref(null);

onMounted(async () => {
    await loadPage(1);
});

const loadPage = async (number) => {
    try {
        useMainStore().setLoading()
        const response = await productsApi.index('?page=' + number);
        products.value = response.data.data.products.data.map(product => ({
            ...product,
            amount: 1
        }));
        nextPageNumber.value = response.data.data.products.next_page_url?.split('=')[1];
        prevPageNumber.value = response.data.data.products.prev_page_url?.split('=')[1];
    } catch (err) {
        toast(`Oops ${err}`, {
            theme: "auto",
            type: "error",
            autoClose: 2000,
        });
    } finally {
        useMainStore().unsetLoading()
    }
};
function removeProducts(id, amount) {
    const index = products.value.findIndex(product => product.id === id);
    if (index === -1) {
        return;
    }
    products.value[index].quantity_left = Math.max(0, products.value[index].quantity_left - amount);

    products.value[index].amount = 1
    if (products.value[index].quantity_left === 0) {
        products.value.splice(index, 1);
    }
}
</script>

<template>
    <MainLayout>
        <template #content>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <a href="products/create" v-if="useAuthStore().isAdmin"
                   class="text-gray-300 font-bold bg-amber-700 px-4 py-2 rounded-2xl mr-10">
                    Create
                </a>
                <ProductSearch/>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <div v-for="product in products" :key="product.id"
                     class="rounded-lg product-card p-2 shadow-xl relative">
                    <Product :product-show="product" :products-all="products"/>
                </div>
            </div>
            <div class="my-6 w-1/2 mx-auto flex items-center justify-evenly">
                <button @click.prevent="loadPage(prevPageNumber)" v-if="prevPageNumber"
                        class="text-white bg-gray-700 rounded-md border-amber-500 px-2 py-1 mr-10">
                    Previous
                </button>
                <button @click.prevent="loadPage(nextPageNumber)" v-if="nextPageNumber"
                        class="text-white bg-gray-700 rounded-md border-amber-500 px-2 py-1 ml-10">
                    Next
                </button>
            </div>
        </template>
    </MainLayout>
</template>
