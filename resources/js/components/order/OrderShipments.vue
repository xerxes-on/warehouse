<script setup>
import {ShipmentStatus} from "@/enums/shipmentStatus.js";

defineProps({
    shipments: {
        required: true,
        type: Object
    }
})
</script>

<template>
    <div class="flex justify-between">
        <div v-for="shipment in shipments" :key="shipment.id" class="border border-gray-300 shadow rounded-2xl relative">
            <div class="py-4 px-2 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-200">Shipment id: {{ shipment.id }}</h2>
                <span
                    class="bg-amber-700 text-white rounded-2xl px-2 mx-2 text-sm ">{{ ShipmentStatus.toString(shipment.status) }}</span>
            </div>
        </div>
    </div>
</template>
