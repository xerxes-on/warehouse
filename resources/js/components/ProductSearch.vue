<script setup>
import {ref, watch} from "vue";
import productsApi from "@/api/products.js";
import {toast} from "vue3-toastify";

const needle = ref("");
const foundProducts = ref([]);

watch(
    () => needle.value,
    async (newQuery) => {
        if (newQuery.length >= 3) {
            try {
                const response = await productsApi.search(newQuery);
                foundProducts.value = response.data.data.products;
            } catch (err) {
                toast(`Oops ${err}`, {
                    theme: "auto",
                    type: "error",
                    autoClose: 2000,
                });
                foundProducts.value = [];
            }
        } else {
            foundProducts.value = [];
        }
    }
);
</script>

<template>
    <div class="py-4">
        <form class="flex items-center max-w-sm mx-auto" @submit.prevent>
            <label for="simple-search" class="sr-only">Search</label>
            <div class="relative w-full">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg
                        class="w-4 h-4 text-gray-500 dark:text-gray-400"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 18 20"
                    >
                        <path
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 5v10M3 5a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm0 10a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm12 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm0 0V6a3 3 0 0 0-3-3H9m1.5-2-2 2 2 2"
                        />
                    </svg>
                </div>
                <input
                    v-model="needle"
                    type="text"
                    id="simple-search"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Search for a product..."
                    required
                />
                <div
                    id="search-results"
                    class="absolute z-10 w-full bg-gray-700 border border-gray-600 rounded-md shadow-lg"
                    v-if="foundProducts.length"
                >
                    <ul class="py-1 text-sm text-gray-200 divide-y divide-gray-600">
                        <li v-for="product in foundProducts" :key="product.id">
                            <a :href="`/products/show/${product.id}`" class="block px-4 py-2 hover:bg-gray-600">
                                {{ product.name }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </form>
    </div>
</template>
