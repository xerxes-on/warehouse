<script setup>
import Navigation from "@/components/navigation/Navigation.vue";
import {useMainStore} from "@/stores/main.js";
import Loading from "@/components/Loading.vue";
</script>
<template>
    <Navigation/>
    <main class="bg-gray-800">
        <Loading v-if="useMainStore().loading"/>
        <slot></slot>
    </main>
</template>
