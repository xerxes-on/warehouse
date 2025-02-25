<script setup>

import Guest from "@/components/layouts/Guest.vue";
</script>

<template>
<Guest>
    <template #content>
        <img :src="'/images/404.png'" alt="404 img" class="w-fit h-fit ">
    </template>
</Guest>
</template>
