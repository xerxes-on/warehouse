<script setup>
import {toast} from "vue3-toastify";
import {onMounted, ref} from "vue";
import {ShipmentStatus} from "@/enums/shipmentStatus.js";
import {OrderStatus} from "@/enums/orderStatus.js";
import ordersApi from "@/api/orders.js";
import {useMainStore} from "@/stores/main.js";
import MainLayout from "@/components/layouts/MainLayout.vue";

const orders = ref()
const props = defineProps({
    status: {
        type: Number,
        default: null
    }
})
onMounted(async () => {
    try {
        useMainStore().setLoading()
        let response;
        if (props.status) {
            response = await ordersApi.getAll({status: props.status});
        } else {
            response = await ordersApi.getAll();
        }
        orders.value = response.data.data.orders
    } catch (err) {
        toast(`Oops ${err}`, {
            theme: "auto",
            type: "error",
            autoClose: 2000,
        });
    } finally {
        useMainStore().unsetLoading()
    }
});
</script>

<template>
    <MainLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between">
                <h1 class="text-2xl font-bold text-white mb-6">Orders</h1>
            </div>
            <div class="grid grid-cols-1 mt-5 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <div v-for="order in orders" :key="order.id" class="border border-gray-300 shadow rounded-lg p-1">
                    <p v-if="order.shipment_id" class="text-amber-500 font-bold">
                        {{ ShipmentStatus.toString(order.shipment.status) }}</p>
                    <a :href="'/orders/show/'+order.id">
                        <div class="p-4 flex justify-between items-center">
                            <h2 class="text-lg font-semibold text-gray-200">Order id: {{ order.id }}</h2>
                            <p class="text-black font-bold bg-amber-500 rounded-2xl py-2 px-3">
                                {{ OrderStatus.toString(order.status) }}</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
