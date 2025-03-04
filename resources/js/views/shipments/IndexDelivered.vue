<script setup>
import Index from "@/components/shipment/Index.vue"
import {ShipmentStatus} from "@/enums/shipmentStatus.js";
</script>

<template>
    <Index :status="ShipmentStatus.DELIVERED"/>
</template>
