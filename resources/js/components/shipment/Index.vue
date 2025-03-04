<script setup>
import {toast} from "vue3-toastify";
import {onMounted, ref} from "vue";
import {ShipmentStatus} from "@/enums/shipmentStatus.js";
import {useMainStore} from "@/stores/main.js";
import MainLayout from "@/components/layouts/MainLayout.vue";
import shipmentsAPi from "../../api/shipment.js";

const shipments = ref()
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
            response = await shipmentsAPi.index({status: props.status});
        } else {
            response = await shipmentsAPi.index();
        }
        shipments.value = response.data.data.shipments
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
                <h1 class="text-2xl font-bold text-white mb-6">Deliveries</h1>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <div v-for="shipment in shipments" :key="shipment.id"
                     class="border border-gray-300 shadow rounded-2xl relative">
                    <RouterLink :to="'/shipments/show/'+shipment.id">
                        <div class="py-4 px-2 flex justify-between items-center">
                            <h2 class="text-lg font-semibold text-gray-200">Shipment id: {{ shipment.id }}</h2>
                            <p class="text-blue-800 font-bold bg-blue-300 rounded-2xl py-2 px-3">
                                {{ ShipmentStatus.toString(shipment.status) }}</p>
                            <span
                                class="bg-amber-700 text-white absolute rounded-2xl px-1 text-sm top-0">#orderId: {{
                                    shipment.order.id
                                }}</span>
                        </div>
                    </RouterLink>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
