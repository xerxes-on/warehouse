<script setup>
import {useAuthStore} from "@/stores/auth.js"
import ordersApi from "@/api/orders.js"
import {toast} from "vue3-toastify"
import {useMainStore} from "@/stores/main.js"
import {computed} from "vue"

const props = defineProps({
    productShow: {
        type: Object,
        required: true
    },
    productsAll: {
        type: Array,
        required: true
    }
})

const product = computed(() => props.productShow)
const products = computed(() => props.productsAll)

const addToCart = async (id, amount) => {
    try {
        useMainStore().setLoading();
        const response = await ordersApi.addProduct({id, quantity: amount})

        if (response.status === 200) {
            toast('Added :)', {
                theme: "auto",
                type: "success",
                autoClose: 2000
            });

            product.value.quantity_left = product.value.quantity_left - amount
            product.value.amount = 1
        }
    } catch (err) {
        toast(`Oops ${err}`, {
            theme: "auto",
            type: "error",
            autoClose: 2000
        })
    } finally {
        useMainStore().unsetLoading();
    }
};
</script>

<template>
    <div v-if="product.quantity_left >= 1">
        <a :href="`/products/show/${product.id}`">
            <img src="/images/cat.jpg"
                 alt="Random Cat"
                 class="h-48 w-full object-cover rounded-t-lg">
        </a>
        <div class="p-4">
            <h2 class="text-lg font-semibold text-gray-200">
                {{ product.name }}
            </h2>
            <div class="mt-2 flex items-center justify-between">
                <p class="text-lg text-white">${{ product.price.toFixed(2) }}</p>
                <p class="px-1 py-1 text-white font-bold text-sm bg-amber-700 rounded-2xl text-right">
                    Left: {{ product.quantity_left }}
                </p>
            </div>
            <div v-if="!useAuthStore().isAdmin"
                 class="mt-3 flex items-center justify-between space-x-2">
                <div>
                    <button type="button"
                            @click="product.amount = Math.max(1, product.amount - 1)"
                            class="text-lg text-white px-2 py-1 rounded">
                        -
                    </button>
                    <input type="number"
                           class="w-16 text-center text-white bg-transparent h-5 border-none"
                           v-model.number="product.amount"
                           min="1"
                           :max="product.quantity_left">
                    <button type="button"
                            @click="product.amount = Math.min(product.quantity_left, product.amount + 1)"
                            class="text-xl text-white px-2 py-1 rounded">
                        +
                    </button>
                </div>
                <button @click="addToCart(product.id, product.amount)" type="button">
                    <i class="fa-solid fa-cart-shopping" style="color: #ff2e2e;"></i>
                </button>
            </div>
        </div>
    </div>
</template>
