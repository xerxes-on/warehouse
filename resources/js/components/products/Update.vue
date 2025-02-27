<script setup>
import {ref} from "vue";
import {toast} from "vue3-toastify";
import productsApi from "@/api/products.js";

const props = defineProps({
    product: {
        required: true,
    }
})

const name = ref(props.product.name);
const newPrice = ref(props.product.price);

const updateProduct = async () => {
    try {
        const response = await productsApi.update(props.product.id, {
            name: name.value,
            price: newPrice.value,
        });
        if (response.status === 200) {
            toast("Product updated successfully!", {
                theme: "auto",
                type: "success",
                autoClose: 2000,
            });
            props.product.name = name.value
            props.product.price = newPrice.value
        }
    } catch (e) {
        toast(`Error: ${e.message || "Failed to update product"}`, {
            theme: "auto",
            type: "error",
            autoClose: 2000,
        });
    }
};

</script>

<template>
    <div class="flex justify-center items-center">
        <form @submit.prevent="updateProduct"
              class="border border-gray-400 rounded-lg p-6 space-y-6 w-full">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-300">Product Name</label>
                <input type="text" v-model="name"
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                       required>
            </div>
            <div>
                <label for="price" class="block text-sm font-medium text-gray-300">Price</label>
                <input type="text" v-model="newPrice"
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                       required>
            </div>
            <div class="text-right">
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
                    Update Product
                </button>
            </div>
        </form>
    </div>
</template>

