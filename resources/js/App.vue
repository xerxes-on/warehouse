<script setup>
import {RouterView} from 'vue-router';
import Loading from "/components/Loading.vue";
import {useMainStore} from "@/stores/main.js";
import 'vue3-toastify/dist/index.css';
</script>
<template>
    <RouterView/>
    <Loading v-if="useMainStore().loading"/>
</template>
