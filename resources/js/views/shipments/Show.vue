<script setup>
import {computed, onMounted, ref} from "vue";
import {useMainStore} from "@/stores/main.js";
import {toast} from "vue3-toastify";
import shipmentsAPi from "@/api/shipment.js";
import {useRoute} from "vue-router";
import MainLayout from "@/components/layouts/MainLayout.vue";
import {ShipmentStatus} from "@/enums/shipmentStatus.js";
import moment from "moment";
import OrderTotals from "@/components/order/OrderTotals.vue";

const route = useRoute()
const shipment = ref()
const isDelivering = computed(() => shipment.value?.status === ShipmentStatus.DELIVERING)
const statusNotReturned = computed(() => shipment.value?.status !== ShipmentStatus.RETURNED)
const calculations = ref()
onMounted(async () => {
    try {
        useMainStore().setLoading()
        const response = await shipmentsAPi.getShipment(route.params.id)
        shipment.value = response.data.data.shipment

        if (shipment.value?.order?.id) {
            const calculationsRes = await shipmentsAPi.calculations(shipment.value.order.id);
            calculations.value = calculationsRes.data.data.calculations;
        }
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
const mark = async (status) => {
    try {
        useMainStore().setLoading()
        const response = await shipmentsAPi.update(shipment.value?.id, {status: status})
        if (response.status === 200) {
            refreshDetails(status)
        }
        toast("Changed successfully", {
            "theme": "auto",
            "type": "success",
            "autoClose": 2000,
        })
    } catch (err) {
        toast(`Oops ${err}`, {
            "theme": "auto",
            "type": "error",
            "autoClose": 2000,
        })
    } finally {
        useMainStore().unsetLoading()
    }
}
const refreshDetails = (status) => {
    shipment.value.status = status
}

</script>

<template>
    <MainLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-white">Shipment #{{ shipment.id }}</h1>
                    <p class="text-gray-400 text-sm">Created:
                        {{ moment(shipment.created_at).format('MMMM Do YYYY, h:mm:ss a') }}</p>
                </div>
                <h1 class="text-center text-amber-500 text-xl border px-2 py-2 rounded-2xl">Shipment Status:
                    <span class="text-white">{{ ShipmentStatus.toString(shipment.status) }}</span>
                </h1>
            </div>
            <div class="bg-gray-800 rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-white mb-2">Shipment Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-300"><strong>To:</strong> {{ shipment.branch.name ?? 'N/A' }}
                            , {{ shipment.branch.location ?? 'N/A' }}</p>
                        <p class="text-gray-300"><strong>From:</strong> {{ shipment.warehouse.name ?? 'N/A' }}
                            , {{ shipment.warehouse.location ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-300">
                            <strong>Sent:</strong> {{ shipment.date_shipped ?? 'Not Shipped Yet' }}
                        </p>
                    </div>
                </div>
            </div>
            <h1 class="text-center text-gray-200 text-xl font-bold mb-4">Order of the shipment </h1>
            <div class="flex px-2 justify-end space-x-2 items-end my-2">
                <div v-if="isDelivering">
                    <button @click="mark(ShipmentStatus.DELIVERED)"
                            class="text-amber-500 text-xl border rounded-xl px-4 py-2 ">
                        Mark as shipped
                    </button>
                </div>
                <div v-if="statusNotReturned">
                    <button @click="mark(ShipmentStatus.RETURNED)"
                            class="text-amber-500 text-xl border rounded-xl px-4 py-2 ">
                        Mark as returned
                    </button>
                </div>
            </div>
            <div class="flex justify-center items-center w-full">
                <div
                    class="border border-gray-300 w-1/2 shadow rounded-lg p-4 bg-gray-700">
                    <a :href="'/orders/show/'+shipment.order.id">
                        <div class="flex justify-between items-start mb-2">
                            <h2 class="text-lg font-semibold text-gray-200">Order #{{ shipment.order.id }}</h2>
                            <p class="bg-amber-700 text-white font-bold rounded-2xl py-1 px-2 text-lg">
                                {{ ShipmentStatus.toString(shipment.order.status) }}</p>
                        </div>
                        <div>
                            <table class="min-w-full divide-y divide-gray-600">
                                <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-white">Product</th>
                                    <th class="px-4 py-2 text-left text-white">Price</th>
                                    <th class="px-4 py-2 text-left text-white">Quantity</th>
                                    <th class="px-4 py-2 text-left text-white">Total</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-600">
                                <tr v-for="item in shipment.shipment_items" :key="item.id">
                                    <td class="px-4 py-2 text-white">{{ item.order_item.product.name }}</td>
                                    <td class="px-4 py-2 text-gray-300">
                                        ${{ item.order_item.product.price ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-2 text-gray-300">
                                        {{ item.order_item.quantity }}
                                    </td>
                                    <td class="px-4 py-2 text-gray-300">
                                        ${{ item.order_item.total_price }}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <OrderTotals v-if="calculations" :totals="calculations"/>
                    </a>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

