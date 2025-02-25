<script setup>
import {ref} from 'vue';
import {onClickOutside} from '@vueuse/core';

const open = ref(false);
const dropdownRef = ref(null);
onClickOutside(dropdownRef, () => {
    open.value = false;
});
</script>
<template>
    <div class="relative">
        <div @click="open = !open">
            <slot name="trigger"></slot>
        </div>

        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-show="open"
                class="absolute z-50 mt-2 rounded-md shadow-lg w-48 py-1 bg-white dark:bg-gray-700"
                @click="open = false"
            >
                <div class="rounded-md flex flex-col  ring-1 ring-black ring-opacity-5 py-1 bg-white dark:bg-gray-700">
                    <slot name="content"></slot>
                </div>
            </div>
        </Transition>
    </div>
</template>
