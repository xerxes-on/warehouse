<script setup>
import Index from "@/components/order/Index.vue";
import {OrderStatus} from "@/enums/orderStatus.js";
</script>

<template>
    <Index :status="OrderStatus.DELIVERED"/>
</template>
