<script setup>
import Navigation from "@/components/navigation/Navigation.vue";
</script>
<template>
    <Navigation/>
    <main class="bg-gray-800">
        <slot name="content">
        </slot>
    </main>
</template>
