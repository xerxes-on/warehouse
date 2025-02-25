<script setup>
import {computed, onMounted, ref} from 'vue';
import {toast} from "vue3-toastify";
import 'vue3-toastify/dist/index.css';
import ordersApi from "@/api/orders.js";
import {OrderStatus} from "@/enums/orderStatus.js";
import MainLayout from "@/components/layouts/MainLayout.vue";
import OrderTotals from "@/components/order/OrderTotals.vue";
import {useMainStore} from "@/stores/main.js";

const selectedProducts = ref([])
const editedProducts = ref([])
const order = ref()
const branches = ref()
const orderItems = ref()
const calculations = ref()
const isStatusCart = computed(() => order.value.status === OrderStatus.CART)

onMounted(async () => {
    try {
        useMainStore().setLoading()
        const response = await ordersApi.getCart()
        if (response.status !== 200) {
            throw new Error('Something went really wrong')
        }
        order.value = response.data.data.cart
        orderItems.value = response.data.data.cart.order_items.map(
            product => ({
                ...product,
                originalAmount: product.quantity
            })
        )
        calculations.value = response.data.data.calculations

        const branchesRes = await ordersApi.getBranches()
        if (response.status !== 200) {
            throw new Error('Something went really wrong with branch api')
        }
        branches.value = branchesRes.data.data.branches
        useMainStore().unsetLoading()
    } catch (err) {
        toast(`Oops ${err}`, {
            "theme": "auto",
            "type": "error",
            "autoClose": 2000,
        })
    }
});

const selectedBranch = ref();
const selectProduct = (id, event) => {
    if (event.target.checked) {
        selectedProducts.value.push(id)
    } else {
        selectedProducts.value = selectedProducts.value.filter(productId => productId !== id)
    }
}
const updateProduct = (productId, event) => {
    const newQuantity = parseInt(event.target.value)
    const originalProduct = orderItems.value.find(p => p.product_id === productId)

    if (newQuantity <= 0) {
        event.target.value = originalProduct.originalAmount
        return
    }
    if (originalProduct.originalAmount !== newQuantity) {
        const existingIndex = editedProducts.value.findIndex(item => item.product_id === productId)

        if (existingIndex >= 0) {
            editedProducts.value[existingIndex].amount = newQuantity
        } else {
            editedProducts.value.push({
                id: productId,
                amount: newQuantity
            })
        }
    } else {
        editedProducts.value = editedProducts.value.filter(item => item.product_id !== productId)
    }
}
const deleteSelected = async () => {
    try {
        useMainStore().setLoading()
        const response = await ordersApi.removeProducts({item_ids: selectedProducts.value})
        if (response.status === 200) {
            toast('Removed', {
                "theme": "auto",
                "type": "success",
                "autoClose": 2000,
            })
            selectedProducts.value = []
            await refreshCartData()
        }
        useMainStore().unsetLoading()
    } catch (err) {
        toast(`Oops ${err}`, {
            "theme": "auto",
            "type": "error",
            "autoClose": 2000,
        })
    }
};
const saveEdited = async () => {
    try {
        useMainStore().setLoading()
        const response = await ordersApi.updateProducts({products: editedProducts.value})
        if (response.status === 200) {
            toast('Updated', {
                "theme": "auto",
                "type": "success",
                "autoClose": 2000,
            })
            await refreshCartData()
        }
        useMainStore().unsetLoading()
    } catch (err) {
        toast(`Oops ${err}`, {
            "theme": "auto",
            "type": "error",
            "autoClose": 2000,
        })
    }
}
const refreshCartData = async () => {
    try {
        const response = await ordersApi.getCart()
        if (response.status !== 200) {
            throw new Error('Something went wrong refreshing cart data')
        }

        order.value = response.data.data.cart
        orderItems.value = response.data.data.cart.order_items.map(
            product => ({
                ...product,
                originalAmount: product.quantity
            })
        )
        calculations.value = response.data.data.calculations
        editedProducts.value = []
    } catch (err) {
        toast(`Failed to refresh cart data: ${err}`, {
            "theme": "auto",
            "type": "error",
            "autoClose": 2000,
        })
    }
}
const placeOrder = async () => {
    try {
        useMainStore().setLoading()
        const response = await ordersApi.changeStatus(order.value.id, {
            branch_id: selectedBranch.value,
            status: OrderStatus.ORDERED
        })
        if (response.status === 200) {
            toast('Ordered', {
                "theme": "auto",
                "type": "success",
                "autoClose": 2000,
            })
        }
        useMainStore().unsetLoading()
    } catch (err) {
        toast(`Oops ${err}`, {
            "theme": "auto",
            "type": "error",
            "autoClose": 2000,
        })
    }
};
</script>
<template>
    <MainLayout>
        <template #content>
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <h1 class="text-2xl text-center font-bold text-white mb-6">
                    Cart
                </h1>
                <div v-if="orderItems?.length">
                    <div class="mb-4">
                        <span class="font-semibold bg-green-300">Status: {{ OrderStatus.toString(order.status) }}</span>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                        <tr>
                            <th v-if="isStatusCart" class="px-4 py-2 text-left text-gray-700 font-bold">Select</th>
                            <th class="px-4 py-2 text-left text-gray-700 font-bold">Product</th>
                            <th class="px-4 py-2 text-left text-gray-700 font-bold">Price</th>
                            <th class="px-4 py-2 text-left text-gray-700 font-bold">Amount</th>
                            <th class="px-4 py-2 text-left text-gray-700 font-bold">Subtotal</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="product in orderItems" :key="product.id">
                            <td v-if="isStatusCart" class="px-4 py-2">
                                <input type="checkbox" @change="selectProduct(product.product_id, $event)"
                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded"/>
                            </td>
                            <td class="px-4 py-2">{{ product.product_name }}</td>
                            <td class="px-4 py-2">{{ product.price }}</td>
                            <td class="px-4 py-2">
                                <input type="number"
                                       class="w-16 font-bold bg-blue-300 h-5 border-none"
                                       v-model="product.quantity"
                                       @change="updateProduct(product.product_id, $event)"
                                       :min="1">
                            </td>
                            <td class="px-4 py-2">${{ product.total_price }}</td>
                        </tr>
                        </tbody>
                    </table>

                    <div v-if="isStatusCart" class="flex items-center justify-end space-x-4 my-4">
                        <button v-if="selectedProducts.length>=1" @click="deleteSelected"
                                class="px-4 py-2 bg-red-800 text-white font-bold rounded">
                            Delete Selected
                        </button>
                        <button
                            v-if="isStatusCart && editedProducts.length >=1"
                            @click="saveEdited"
                            class="px-4 py-2 text-white font-bold bg-blue-600  rounded">
                            Save Changes
                        </button>
                    </div>
                    <OrderTotals :totals="calculations"/>
                    <div v-if="isStatusCart" class="flex items-center justify-end space-x-4 my-4">
                        <label class="mr-4 text-white font-bold">Branch:{{ selectedBranch }}</label>
                        <select v-model="selectedBranch" class="mx-2 bg-transparent text-white font-bold rounded-xl">
                            <option v-for="branch in branches" :key="branch.id" :value="branch.id">{{
                                    branch.name
                                }}
                            </option>
                        </select>
                        <button @click="placeOrder"
                                class="px-6 py-2 bg-blue-600 font-bold text-xl  text-white rounded-xl">Order
                        </button>
                    </div>
                </div>
                <div v-else class="flex items-center justify-center w-full h-fit">
                    <img :src="'/images/empty.gif'" alt="No items" class="rounded-2xl"/>
                </div>
            </div>
        </template>
    </MainLayout>
</template>
