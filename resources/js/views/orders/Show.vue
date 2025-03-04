<script setup>
import {onMounted, ref} from 'vue';
import {toast} from "vue3-toastify";
import 'vue3-toastify/dist/index.css';
import ordersApi from "@/api/orders.js";
import {OrderStatus} from "@/enums/orderStatus.js";
import MainLayout from "@/components/layouts/MainLayout.vue";
import OrderTotals from "@/components/order/OrderTotals.vue";
import {useMainStore} from "@/stores/main.js";
import OrderShipments from "@/components/order/OrderShipments.vue";
import {useRoute, useRouter} from "vue-router";

const order = ref()
const calculations = ref()
const route = useRoute()
const router = useRouter()
onMounted(async () => {
    try {
        useMainStore().setLoading()
        const response = await ordersApi.getOrder(route.params.id)
        if (response.status === 404) {
            useMainStore().unsetLoading()
            await router.push('not-found')
        }
        order.value = response.data.data.order
        calculations.value = response.data.data.details
    } catch (err) {
        toast(`Oops ${err}`, {
            "theme": "auto",
            "type": "error",
            "autoClose": 2000,
        })
    } finally {
        useMainStore().unsetLoading()
    }
});

</script>
<template>
    <MainLayout>
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <h1 class="text-2xl text-center font-bold text-white mb-6">
                Order #{{ order?.id }}
            </h1>
            <OrderShipments :shipments="order?.shipments"/>
            <div class="mt-4">
                <div class="mb-4">
                        <span class="font-semibold bg-green-300">Status: {{
                                OrderStatus.toString(order?.status)
                            }}</span>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-700 font-bold">Product</th>
                        <th class="px-4 py-2 text-left text-gray-700 font-bold">Price</th>
                        <th class="px-4 py-2 text-left text-gray-700 font-bold">Amount</th>
                        <th class="px-4 py-2 text-left text-gray-700 font-bold">Subtotal</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="product in order?.order_items" :key="product.id">
                        <td class="px-4 py-2">{{ product.product_name }}</td>
                        <td class="px-4 py-2">{{ product.price }}</td>
                        <td class="px-4 py-2">
                            {{ product.quantity }}
                        </td>
                        <td class="px-4 py-2">${{ product.total_price }}</td>
                    </tr>
                    </tbody>
                </table>
                <OrderTotals v-if="calculations" :totals="calculations"/>
            </div>
        </div>
    </MainLayout>
</template>
